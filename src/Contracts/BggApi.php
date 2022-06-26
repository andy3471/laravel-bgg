<?php

namespace AndyH\LaravelBgg\Contracts;

interface BggApi
{
    static function transformBggDetails($details);

    static function getBggType();

}