<?php

namespace Bpwd\Admin\Models\Users\Fields;

use Bpwd\Admin\Models\Users\Permission AS Model;

class Permission{

    public function getOptions()
    {
        return Model::lists('name', 'id')->toArray();
    }

}