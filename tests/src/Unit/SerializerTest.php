<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Spiral\SerializableClosure\Serializer;
use Spiral\SerializableClosure\Tests\App\ChildClass;
use Spiral\SerializableClosure\Tests\App\Entity;
use Spiral\SerializableClosure\Tests\App\PrivateClone;
use Spiral\SerializableClosure\Tests\App\Some;

final class SerializerTest extends TestCase
{
    private Serializer $serializer;

    protected function setUp(): void
    {
        $this->serializer = new Serializer();
    }

    public function testSerialization(): void
    {
        $object = new \stdClass();
        $object->foo = 'bar';

        $serializedPayload = $this->serializer->serialize([
            'int' => 1,
            'string' => 'foo',
            'array' => ['foo'],
            'object' => $object,
            'closure' => function () use ($object) {
                return $object;
            },
        ]);

        $this->assertIsArray(
            $payload = $this->serializer->unserialize($serializedPayload)
        );

        $this->assertSame(1, $payload['int']);
        $this->assertSame('foo', $payload['string']);
        $this->assertInstanceOf(get_class($object), $payload['object']);
        $this->assertTrue($payload['closure'] instanceof \Closure);
    }

    public function testSerializationClosure(): void
    {
        $f =  fn ($value) => $value;

        $a = new Some($f);
        $u = $this->serializer->unserialize($this->serializer->serialize($a));
        $this->assertTrue($u->test(true));
    }

    public function testSerializationSameObjects(): void
    {
        $f = fn ($value) => $value;

        $i = new Some($f);
        $a = [$i, $i];
        $u = $this->serializer->unserialize($this->serializer->serialize($a));

        $this->assertTrue($u[0] === $u[1]);
    }

    public function testSerializationSameClosures(): void
    {
        $f =  fn ($value) => $value;

        $i = new Some($f);
        $a = [$i, $i];
        $u = $this->serializer->unserialize($this->serializer->serialize($a));
        $this->assertTrue($u[0]->getF() === $u[1]->getF());
    }

    public function testPrivateMethodClone(): void
    {
        $a = new PrivateClone();
        $u = $this->serializer->unserialize($this->serializer->serialize($a));
        $this->assertEquals(1, $u->value());
    }

    public function testPrivateMethodClone2(): void
    {
        $a = new PrivateClone();
        $f = fn () => $a->value();
        $u = $this->serializer->unserialize($this->serializer->serialize($f));
        $this->assertEquals(1, $u());
    }

    public function testNestedObjects(): void
    {
        $parent = new Entity();
        $child = new Entity();
        $parent->children[] = $child;
        $child->parent = $parent;

        $f = fn () => $parent === $child->parent;

        $u = $this->serializer->unserialize($this->serializer->serialize($f));
        $this->assertTrue($u());
    }

    public function testNestedObjects2(): void
    {
        $child = new \stdClass();
        $parent = new \stdClass();
        $child->parent = $parent;
        $parent->childern = [$child];
        $parent->closure = fn () => true;
        $u = $this->serializer->unserialize($this->serializer->serialize($parent))->closure;
        $this->assertTrue($u());
    }

    public function testPrivatePropertyInParentClass(): void
    {
        $instance = new ChildClass();

        $closure = function () use ($instance) {
            return $instance->getFoobar();
        };

        $u = $this->serializer->unserialize($this->serializer->serialize($closure));
        $this->assertSame(['test'], $u());
    }

    public function testInternalClass1(): void
    {
        $date = new \DateTime();
        $date->setDate(2018, 2, 23);

        $closure = fn () => $date->format('Y-m-d');

        $u = $this->serializer->unserialize($this->serializer->serialize($closure));
        $this->assertEquals('2018-02-23', $u());
    }

    public function testInternalClass2(): void
    {
        $date = new \DateTime();
        $date->setDate(2018, 2, 23);
        $instance = (object)['date' => $date];
        $closure = fn () => $instance->date->format('Y-m-d');

        $u = $this->serializer->unserialize($this->serializer->serialize($closure));
        $this->assertEquals('2018-02-23', $u());
    }

    public function testInternalClass3(): void
    {
        $date = new \DateTime();
        $date->setDate(2018, 2, 23);
        $instance = (object)['date' => $date];

        $u = $this->serializer->unserialize($this->serializer->serialize($instance));
        $this->assertEquals('2018-02-23', $u->date->format('Y-m-d'));
    }

    public function testObjectReserialization(): void
    {
        $o = (object)['foo'=>'bar'];
        $s1 = $this->serializer->serialize($o);
        $o = $this->serializer->unserialize($s1);
        $s2 = $this->serializer->serialize($o);

        $this->assertEquals($s1, $s2);
    }

    public function testIfThisIsCorrectlySerialized(): void
    {
        $o = new PrivateClone();
        $f = $o->create();
        $f = $this->serializer->unserialize($this->serializer->serialize($f));
        $f = $this->serializer->unserialize($this->serializer->serialize($f));
        $this->assertEquals(1, $f());
    }
}
