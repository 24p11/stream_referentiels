<?php


namespace App\Serializer;


class CircularReferenceHandler
{
    public function __invoke($object)
    {
        switch ($object) {

        }

        return $object->getId();
    }

}
