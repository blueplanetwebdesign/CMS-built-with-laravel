<?php

use App\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use tests\Acceptance\Admin\AdminTrait;

class AcceptanceAdminShoppingProductsTest extends AcceptanceTestCase
{
    use AdminTrait;
    
    private $list_view = '/admin/shopping/products';
    private $create_view = '/admin/shopping/products/create';
    
    public function setUp()
    {
        parent::setUp();
        
    }
        
    private function clickFirstCheckboxOnTable()
    {
        $this->byXPath(".//*[@id='admin-form']/table/tbody/tr[1]/td[1]/div/ins")->click();
    }
    
    private function visitListView()
    {
        $this->url($this->list_view);
    }
    
    private function visitEditView()
    {
        self::visitListView();
        self::clickFirstCheckboxOnTable();
        $this->byId('toolbar-edit')->click();
    }
    
    public function testCloseButton()
    {
        self::visitEditView();
        $this->byId('toolbar-close')->click();
        $this->assertStringEndsWith($this->list_view, $this->url());
    }
    
    public function testProductListPageExist()
    {
        self::visitListView();
        $this->assertContains('Shopping -> Product List', $this->byCssSelector('.page-title')->text());
        $this->assertStringEndsWith($this->list_view, $this->url());
    }
    
    /*
    public function testSaveAndNewButton()
    {
        self::visitEditView();
        $this->byId('toolbar-save-and-new')->click();
        $element = $this->byCssSelector('.flash-message');
        $this->assertContains('Product saved!', $element->text());
        $this->assertStringEndsWith($this->create_view, $this->url());
    }
    
    public function testNewButton()
    {
        self::visitListView();
        $this->byId('toolbar-new')->click();
        $this->assertEquals('Shopping -> Product New', $this->byCssSelector('.page-title')->text());
        $this->assertStringEndsWith($this->create_view, $this->url());
    }
    
    public function testEditButton()
    {
        self::visitEditView();
        $this->assertEquals('Shopping -> Product Edit', $this->byCssSelector('.page-title')->text());
        $this->assertStringEndsWith('edit', $this->url());
    }
    
    public function testSaveButton()
    {
        self::visitEditView();
        $this->byId('toolbar-save')->click();
        $element = $this->byCssSelector('.flash-message');
        $this->assertContains('Product saved!', $element->text());
        $this->assertStringEndsWith('edit', $this->url());
    }
    
    public function testSaveAndCloseButton()
    {
        self::visitEditView();
        $this->byId('toolbar-save-and-close')->click();
        $element = $this->byCssSelector('.flash-message');
        $this->assertContains('Product saved!', $element->text());
        $this->assertStringEndsWith($this->list_view, $this->url());
    }
    
    public function testEditLink()
    {
        self::visitListView();
        $this->byXPath(".//*[@id='admin-form']/table/tbody/tr[1]/td[2]/a")->click();
        $this->assertStringEndsWith('edit', $this->url());
    }
    
    public function testPublishButton()
    {
        self::visitListView();
        self::clickFirstCheckboxOnTable();
        $this->byId('toolbar-publish')->click();
        $element = $this->byCssSelector('.flash-message');
        $this->assertEquals('1 item published.', $element->text());
        $this->assertStringEndsWith($this->list_view, $this->url());
    }
    
    public function testUnpublishButton()
    {
        self::visitListView();
        self::clickFirstCheckboxOnTable();
        $this->byId('toolbar-unpublish')->click();
        $element = $this->byCssSelector('.flash-message');
        $this->assertEquals('1 item unpublished.', $element->text());
        $this->assertStringEndsWith($this->list_view, $this->url());
    }
        
    public function testDeleteButton()
    {
        self::visitListView();
        self::clickFirstCheckboxOnTable();
        $this->byId('toolbar-delete')->click();
        $element = $this->byCssSelector('.flash-message');
        $this->assertEquals('1 item deleted.', $element->text());
        $this->assertStringEndsWith($this->list_view, $this->url());
        $this->assertStringEndsWith($this->list_view, $this->url());
    }
    */
    
}
