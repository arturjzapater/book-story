<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class OrdersApiTest extends TestCase
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
        $this->prepareDB(6, 6);

        $data = [
            'name' => 'Test Joe',
            'email' => 'test@test.com',
            'phone' => '123456789',
            'address' => 'Test Street 2',
            'postal_code' => 11111,
        ];

        $this->post('/api/orders', array_merge($data, [ 'cart' => 1 ]))
            ->seeJson($data)
            ->seeInDatabase('orders', $data)
            ->seeInDatabase('order_product', [
                'order_id' => 1,
                'product_id' => 1,
            ])
            ->seeInDatabase('order_product', [
                'order_id' => 1,
                'product_id' => 2,
            ])
            ->seeInDatabase('order_product', [
                'order_id' => 1,
                'product_id' => 3,
            ])
            ->seeInDatabase('order_product', [
                'order_id' => 1,
                'product_id' => 4,
            ])
            ->notSeeInDatabase('carts', [ 'id' => 1 ])
            ->assertResponseStatus(201);
    }
}
