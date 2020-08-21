<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class CartsApiTest extends TestCase
{
    use DatabaseMigrations;

    private function prepareDB($prods, $prods_in_cart)
    {
        factory('App\Product', $prods)->create();
        factory('App\Cart', 1)->create();
        factory('App\CartProduct', $prods_in_cart)->create();
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
        $this->prepareDB(6, 4);

        $this->call('GET', '/api/carts/1');
        $decoded = json_decode($this->response->getContent());

        $this->assertEquals(200, $this->response->status());
        $this->assertEquals(1, $decoded->id);
        $this->assertEquals(4, count($decoded->products));
    }

    public function testGetNotFound()
    {
        $this->get('/api/carts/NotFound')
            ->seeJson([ 'message' => 'Not Found' ])
            ->assertResponseStatus(404);
    }

    public function testUpdate()
    {
        $this->prepareDB(8, 4);

        $this->put('/api/carts/1', [ 'product' => 8 ])
            ->seeInDatabase('cart_product', [
                'cart_id' => 1,
                'product_id' => 8,
            ])
            ->assertResponseStatus(200);
    }

    public function testUpdateNonexistent()
    {
        $this->put('/api/carts/10', [ 'product' => 1 ])
            ->seeJson([ 'message' => 'Bad Request' ])
            ->notSeeInDatabase('cart_product', [
                'cart_id' => 1,
                'product_id' => 1,
            ])
            ->assertResponseStatus(400);
    }

    public function testUpdateDelete()
    {
        $this->prepareDB(6, 6);

        $this->put('/api/carts/1/?action=delete', [ 'product' => 1 ])
            ->notSeeInDatabase('cart_product', [
                'cart_id' => 1,
                'product_id' => 1,
            ])
            ->assertResponseStatus(200);
    }

    public function testDelete()
    {
        $this->prepareDB(1, 1);

        $this->delete('/api/carts/1')
            ->seeJson([ 'message' => 'Successfully deleted' ])
            ->notSeeInDatabase('carts', [ 'id' => 1 ])
            ->assertResponseStatus(200);
    }

    public function testDeleteNonexistent()
    {
        $this->delete('/api/carts/2')
            ->seeJson([ 'message' => 'Successfully deleted' ])
            ->notSeeInDatabase('carts', [ 'id' => 1 ])
            ->assertResponseStatus(200);
    }

    public function testGetCount()
    {
        $this->prepareDB(7, 3);

        $this->get('/api/carts/1/itemcount')
            ->seeJson([ 'count' => 3 ])
            ->assertResponseStatus(200);
    }
}
