<?php

namespace Bpwd\Admin\Models\Users\Fields;

use Bpwd\Admin\Models\Users\Role AS Model;
use DB;

class Role{

    protected $table = 'roles';

    public function getOptions()
    {
        return Model::lists('name', 'id')->toArray();
        //return Model::select('name', 'id')->pluck('name','id')->toArray();
    }

}