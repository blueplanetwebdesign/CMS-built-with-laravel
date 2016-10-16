<?php

namespace Bpwd\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class LayoutHelper extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LayoutHelper'; // the IoC binding.
    }

}