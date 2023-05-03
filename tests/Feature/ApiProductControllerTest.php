<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testProductsIndex(): void
    {
        Product::factory()->create();

        $response = $this->get(route('api.products.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'subcategory',
                    'stock',
                    'score',
                    'status',
                    'barCode',
                ],
            ],
        ]);
    }
}
