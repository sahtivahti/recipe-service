<?php
declare(strict_types = 1);

namespace App\Helpers;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        return null;
    }
}
