<?php

use App\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use tests\Acceptance\Admin\AdminTrait;

class AcceptanceAdminTest extends AcceptanceTestCase
{
    use AdminTrait;
    
    public function setUp()
    {
        parent::setUp();
    }
        
    public function testLogIn()
    {
        $this->assertStringEndsWith('admin', $this->url());
    }
    
}
