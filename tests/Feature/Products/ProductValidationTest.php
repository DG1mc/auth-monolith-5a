<?php

use App\Models\User;
use Modules\Products\Models\Product;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guest is redirected to login', function () {
    app('auth')->forgetGuards();

    $this->get(route('products.index'))->assertRedirect(route('login'));
});

test('product pages can be rendered', function () {
    $product = Product::factory()->create();

    $this->get(route('products.index'))->assertOk()->assertSee($product->name);
    $this->get(route('products.create'))->assertOk();
    $this->get(route('products.edit', $product))->assertOk();
    $this->get(route('products.show', $product))->assertOk();
});

test('store rejects empty form with indonesian messages', function () {
    $this->from(route('products.create'))
        ->post(route('products.store'), [])
        ->assertRedirect(route('products.create'))
        ->assertSessionHasErrors([
            'name' => 'Nama produk wajib diisi.',
            'price' => 'Harga produk wajib diisi.',
            'stock' => 'Stok wajib diisi.',
        ]);
});

test('store rejects duplicate slug', function () {
    Product::factory()->create(['slug' => 'kopi-arabika']);

    $this->post(route('products.store'), [
        'name' => 'Kopi', 'slug' => 'kopi-arabika', 'price' => 1000, 'stock' => 1,
    ])->assertSessionHasErrors(['slug' => 'Slug produk sudah digunakan.']);
});

test('store rejects negative price and non integer stock', function () {
    $this->post(route('products.store'), [
        'name' => 'Kopi', 'slug' => 'kopi', 'price' => -1, 'stock' => 1.5,
    ])->assertSessionHasErrors([
        'price' => 'Harga tidak boleh negatif.',
        'stock' => 'Stok harus berupa bilangan bulat.',
    ]);
});

test('store creates product with valid data', function () {
    $this->post(route('products.store'), [
        'name' => 'Kopi Gayo', 'slug' => 'kopi-gayo', 'price' => 85000, 'stock' => 10,
    ])->assertRedirect(route('products.index'))->assertSessionHas('success');

    $this->assertDatabaseHas('products', ['slug' => 'kopi-gayo', 'stock' => 10]);
});

test('update allows keeping own slug but rejects another products slug', function () {
    $product = Product::factory()->create(['slug' => 'milik-saya']);
    Product::factory()->create(['slug' => 'milik-orang']);

    $this->put(route('products.update', $product), ['slug' => 'milik-saya', 'name' => 'Baru'])
        ->assertSessionDoesntHaveErrors('slug');

    $this->put(route('products.update', $product), ['slug' => 'milik-orang'])
        ->assertSessionHasErrors('slug');
});

test('reduce stock fails with custom exception when stock is insufficient', function () {
    $product = Product::factory()->create(['name' => 'Gula', 'stock' => 3]);

    $this->from(route('products.show', $product))
        ->post(route('products.reduce-stock', $product), ['quantity' => 5])
        ->assertRedirect(route('products.show', $product))
        ->assertSessionHas('error', "Stok produk 'Gula' tidak mencukupi. Tersedia: 3, diminta: 5.");

    expect($product->fresh()->stock)->toBe(3);
});

test('reduce stock returns json 422 for json requests', function () {
    $product = Product::factory()->create(['stock' => 2]);

    $this->postJson(route('products.reduce-stock', $product), ['quantity' => 9])
        ->assertStatus(422)
        ->assertJson([
            'error' => 'insufficient_stock',
            'details' => ['available' => 2, 'requested' => 9],
        ]);
});

test('reduce stock decrements stock when enough', function () {
    $product = Product::factory()->create(['stock' => 10]);

    $this->post(route('products.reduce-stock', $product), ['quantity' => 4])
        ->assertSessionHas('success');

    expect($product->fresh()->stock)->toBe(6);
});
