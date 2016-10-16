<?php

use App\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Auth;

class FunctionalAdminTest extends FunctionalTestCase
{
    
    public function setUp()
    {
        
        parent::setUp();
        $user = User::where('email', '=', 'andrew@blueplanetwebdesign.co.uk')->first();
        Auth::login($user);
    }
    
    public function testAreWeLoggedIn()
    {
        $this->assertEquals(Auth::check(), 1);

    }

    public function testAdminRedirect()
    {
        
        $this->visit('admin')
            ->seePageIs('/admin');
    }
    
}
