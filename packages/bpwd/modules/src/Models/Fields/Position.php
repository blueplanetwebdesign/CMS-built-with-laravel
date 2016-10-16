<?php

namespace Bpwd\Modules\Models\Fields;

use Bpwd\Shopping\Admin\Models\Category AS Model;
use DB;

class Position{

    public function getOptions()
    {
        return array(
                        'header' => 'Header',
                        'top-menu' => 'Top Menu'
                    );
    }

}