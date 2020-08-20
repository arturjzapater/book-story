<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CartsApiTest extends TestCase
{
    use DatabaseMigrations;

    private function prepareDB($num)
    {
        factory('App\Product', $num)->create();
        factory('App\Cart', 1)->create();
        factory('App\ProductCart', 4)->create();
    }
    
    public function testCreate()
    {
        $this->call('POST', '/api/carts');
        $decoded = json_decode($this->response->getContent());

        $this->assertEquals(201, $this->response->status());
        $this->assertEquals(
            "/api/carts/$decoded->id",
            $this->response->headers->get('Location')
        );
        $this->assertTrue(empty($decoded->items));
        $this->seeInDatabase('carts', [
            'id' => $decoded->id,
        ]);
    }

    public function testGetOne()
    {
        $this->prepareDB(6);

        $this->call('GET', '/api/carts/1');
        $decoded = json_decode($this->response->getContent());

        $this->assertEquals(200, $this->response->status());
        $this->assertEquals(1, $decoded->id);
        $this->assertEquals(4, count($decoded->items));
    }
}
