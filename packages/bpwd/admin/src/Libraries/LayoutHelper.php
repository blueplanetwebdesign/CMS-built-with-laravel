<?php

namespace Bpwd\Admin\Libraries;

class LayoutHelper
{
    public function treePrefix($depth)
    {
        if ($depth > 1) echo '<span class="muted">' . str_repeat('&#9482;&nbsp;&nbsp;&nbsp;', (int) $depth - 2) . '</span>&ndash;&nbsp;';
    }
}