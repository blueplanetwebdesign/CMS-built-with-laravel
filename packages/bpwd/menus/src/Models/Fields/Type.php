<?php

namespace Bpwd\Menus\Models\Fields;

use Bpwd\Menus\Models\Type AS Model;

class Type{

    public function getOptions()
    {
        return Model::lists('name', 'id')->toArray();
        //return Model::select('name', 'id')->pluck('name','id')->toArray();
    }

}