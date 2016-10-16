<?php

namespace Tests;

use Tests\TestCase;

use App\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminAuthTest extends TestCase
{

    public function testAdminRedirect()
    {
        $this->visit('/admin')
             ->seePageIs('admin/login');
    }
    
    public function testAdminLogin()
    {
        $this->visit('/admin')
            ->type('andrew@blueplanetwebdesign.co.uk', 'email')
            ->type('millie/1', 'password')
            ->press('Login')
            ->seePageIs('/admin');
    }
}
