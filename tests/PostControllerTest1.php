<?php

class PostControllerTest extends TestCase
{
    public function testIndex()
    {
        //$this->client->request('GET', 'posts');
        $this->call('GET', 'posts');
    }
}
