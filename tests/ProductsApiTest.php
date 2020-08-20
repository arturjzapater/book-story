<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ProductsApiTest extends TestCase
{
    use DatabaseMigrations;

    private function addProducts($num)
    {
        factory('App\Product', $num)->create();
    }
    
    public function testGetAll()
    {
        $product_num = 6;
        $this->addProducts($product_num);

        $this->call('GET', '/api/products');
        $decoded = json_decode($this->response->getContent());

        $this->assertEquals(200, $this->response->status());
        $this->assertEquals($product_num, count($decoded));
    }

    public function testGetOne()
    {
        $product_id = 2;
        $product_num = 6;
        $this->addProducts($product_num);

        $this->call('GET', '/api/products/' . $product_id);
        $decoded = json_decode($this->response->getContent());

        $this->assertEquals(200, $this->response->status());
        $this->seeInDatabase('products', [
            'id' => $product_id,
            'title' => $decoded->title,
            'author' => $decoded->author,
            'price' => $decoded->price,
        ]);
    }

    public function testGetNotFound()
    {
        $this->call('GET', '/api/products/NotFound');
        $decoded = json_decode($this->response->getContent());

        $this->assertEquals(404, $this->response->status());
        $this->assertEquals('Not Found', $decoded->message);
    }
}
