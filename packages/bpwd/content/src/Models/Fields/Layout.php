<?php

namespace Bpwd\Content\Models\Fields;

class Layout{

    public function getOptions($display_default = false)
    {
        if($display_default) $layouts[0] = 'Not defined';
        $layouts[1] = 'None';
        $layouts[2] = 'Home';
        return $layouts;
    }

}