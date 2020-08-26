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
                'quantity' => 1,
            ])
            ->seeInDatabase('order_product', [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
            ])
            ->seeInDatabase('order_product', [
                'order_id' => 1,
                'product_id' => 3,
                'quantity' => 1,
            ])
            ->seeInDatabase('order_product', [
                'order_id' => 1,
                'product_id' => 4,
                'quantity' => 1,
            ])
            ->notSeeInDatabase('carts', [ 'id' => 1 ])
            ->assertResponseStatus(201);
    }

    public function testCreateReturnsErrorOnMissingFields()
    {
        $this->prepareDB(6, 6);

        $expected = [
            'address' => [ 'The address field is required.' ],
            'postal_code' => [ 'The postal code field is required.' ],
            'email' => [ 'The email field is required.' ],
            'phone' => [ 'The phone field is required.' ],
        ];

        $this->post('/api/orders', [ 'cart' => 1, 'name' => 'John Test' ])
            ->seeJson($expected)
            ->seeInDatabase('carts', [ 'id' => 1])
            ->notSeeInDatabase('orders', [ 'name' => 'John Test'])
            ->assertResponseStatus(422);
    }

    public function testCreateReturnsErrorOnBadInput()
    {
        $this->prepareDB(6, 6);

        $data = [
            'cart' => 1,
            'name' => 'John Test',
            'c_o' => 'Julia Test',
            'address' => 'Test Street 5',
            'postal_code' => '123456',
            'email' => 'Yo',
            'phone' => '123456789'
        ];

        $expected = [
            'postal_code' => [ 'The postal code must be 5 digits.' ],
            'email' => [ 'The email must be a valid email address.' ],
        ];

        $this->post('/api/orders', $data)
            ->seeJson($expected)
            ->seeInDatabase('carts', [ 'id' => 1])
            ->notSeeInDatabase('orders', [ 'name' => 'John Test'])
            ->assertResponseStatus(422);
    }

    public function testCreateReturnsErrorOnWrongCart()
    {
        $this->prepareDB(6, 6);

        $data = [
            'cart' => 'NotFound',
            'name' => 'Julia Test',
            'email' => 'test@test.com',
            'phone' => '123456789',
            'address' => 'Test Street 2',
            'postal_code' => '12345',
        ];

        $expected = [
            'message' => 'Cart does not exist'
        ];

        $this->post('/api/orders', $data)
            ->seeJson($expected)
            ->seeInDatabase('carts', [ 'id' => 1])
            ->notSeeInDatabase('orders', [ 'name' => 'Julia Test'])
            ->assertResponseStatus(400);
    }
}
