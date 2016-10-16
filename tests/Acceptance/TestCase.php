<?php

class AcceptanceTestCase extends PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost:8000');
    }

    /*public function testTitle()
    {
        $this->url('http://localhost:8000');
        sleep(2);
        $this->assertEquals('Laravel', $this->title());
    }*/

}
?>