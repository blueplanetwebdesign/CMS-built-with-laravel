<?php

namespace Bpwd\Shopping\Admin\Models\Fields;

use Bpwd\Shopping\Admin\Models\Category AS Model;
use DB;

class Category{

    public function getOptions()
    {
        return Model::lists('name', 'id')->toArray();
        //return Model::select('name', 'id')->pluck('name','id')->toArray();
    }

}