<?php

namespace AndyH\LaravelBgg\Contracts;

interface BggApi
{
    public static function transformBggDetails($details);

    public static function getBggType();
}
