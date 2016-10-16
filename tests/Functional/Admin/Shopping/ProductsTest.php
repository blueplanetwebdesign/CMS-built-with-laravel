<?php

use Tests\Functional\AdminTest\AdminTest;

use App\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FunctionalAdminShoppingProductsTest extends FunctionalAdminTest
{
    
    public function setUp()
    {
        parent::setUp();
        $this->visit('/admin/shopping/products');
    }
    
    public function testProductListPageExist()
    {
        $this->assertResponseOk();
    }
    
    public function testNewButton()
    {
        echo $this->currentUri;
        
        $this->visit('/admin/shopping/products')
            ->press('toolbar-new');
            //->seePageIs('admin/shopping/products/create');
    }
}
