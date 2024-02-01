<?php

namespace ReesMcIvor\ImageTools\Facades;

use Illuminate\Support\Facades\Facade;

class ImageTools extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'imagetools';
    }
}
