<?php

namespace Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Modules\Products\Exceptions\InsufficientStockException;
use Modules\Products\Models\Product;
use Modules\Products\Requests\ReduceStockRequest;
use Modules\Products\Requests\StoreProductRequest;
use Modules\Products\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function reduceStock(ReduceStockRequest $request, Product $product): RedirectResponse
    {
        $quantity = $request->integer('quantity');

        DB::transaction(function () use ($product, $quantity): void {
            $locked = Product::whereKey($product->id)->lockForUpdate()->firstOrFail();

            if ($locked->stock < $quantity) {
                throw new InsufficientStockException($locked->name, $locked->stock, $quantity);
            }

            $locked->decrement('stock', $quantity);
        });

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Stok berhasil dikurangi.');
    }
}
