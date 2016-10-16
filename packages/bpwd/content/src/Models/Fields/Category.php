<?php

namespace Bpwd\Content\Models\Fields;

use Bpwd\Content\Models\Category AS Model;
use DB;

class Category{

    public function getOptions()
    {
        return Model::lists('name', 'id')->toArray();
        //return Model::select('name', 'id')->pluck('name','id')->toArray();
    }

}