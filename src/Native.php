<?php

declare(strict_types=1);

namespace Spiral\SerializableClosure;

use Laravel\SerializableClosure\Serializers\Native as BaseNative;
use Laravel\SerializableClosure\Support\ClosureScope;

class Native extends BaseNative
{
    public static function unwrapClosures(&$data, ClosureScope $storage)
    {
        if($data instanceof static) {
            $data = $data->getClosure();
        } elseif (\is_array($data)) {
            if(isset($data[self::ARRAY_RECURSIVE_KEY])){
                return;
            }
            $data[self::ARRAY_RECURSIVE_KEY] = true;
            foreach ($data as $key => &$value){
                if($key === self::ARRAY_RECURSIVE_KEY){
                    continue;
                }
                static::unwrapClosures($value, $storage);
            }
            unset($data[self::ARRAY_RECURSIVE_KEY]);
        } elseif ($data instanceof \stdClass) {
            if(isset($storage[$data])) {
                return;
            }
            $storage[$data] = true;
            foreach ($data as &$property) {
                static::unwrapClosures($property, $storage);
            }
        } elseif (\is_object($data) && !($data instanceof \Closure)) {
            if(isset($storage[$data])) {
                return;
            }
            $storage[$data] = true;
            $reflection = new \ReflectionObject($data);

            do {
                if(!$reflection->isUserDefined()){
                    break;
                }
                foreach ($reflection->getProperties() as $property) {
                    if($property->isStatic() || !$property->getDeclaringClass()->isUserDefined()) {
                        continue;
                    }

                    if (!$property->isInitialized($data)) {
                        continue;
                    }
                    $value = $property->getValue($data);
                    if(\is_array($value) || \is_object($value)) {
                        static::unwrapClosures($value, $storage);
                        $property->setValue($data, $value);
                    }
                };
            } while($reflection = $reflection->getParentClass());
        }
    }
}
