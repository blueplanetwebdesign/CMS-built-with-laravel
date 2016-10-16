<?php

namespace tests\Acceptance\Admin;

trait AdminTrait
{
    public function setUpPage()
    {
        $cookies = $this->cookie();
        $cookies->remove('laravel_session');
        
        $this->url('admin');
        $this->byCssSelector('input[name="email"]')->value('andrew@blueplanetwebdesign.co.uk');
        $this->byCssSelector('input[name="password"]')->value('millie/1');
        $this->byCssSelector('form')->submit();
    }    
}
