<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Products\ProductsService;
use App\Services\Categories\CategoriesService;
use App\Services\Suppilers\SuppilersService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductsService $productService,
        protected CategoriesService $categoryService,
        protected SuppilersService $supplierService
    ) {}

    public function index(Request $request)
    {
        $filters  = $request->only(['search', 'category_id', 'supplier_id', 'is_active', 'stock_status', 'sort_by', 'sort_dir']);
        $products = $this->productService->getAllProducts($filters, 15);
        $categories = $this->categoryService->getAllCategories(true);
        $suppliers  = $this->supplierService->getAllSuppliers(true);

        return view('admin.products.index', compact('products', 'categories', 'suppliers', 'filters'));
    }

    public function create()
    {
        $categories = $this->categoryService->getAllCategories(true);
        $suppliers  = $this->supplierService->getAllSuppliers(true);
        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        if (!$request->filled('code')) {
            $request->merge([
                'code' => 'PRD-' . strtoupper(\Illuminate\Support\Str::random(8))
            ]);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'code'        => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'buy_price'   => 'required|numeric|min:0',
            'sell_price'  => 'required|numeric|min:0',
            'min_stock'   => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'attributes'  => 'nullable|array',
            'attributes.*.attribute_name'  => 'required_with:attributes|string|max:100',
            'attributes.*.attribute_value' => 'required_with:attributes|string|max:255',
        ]);

        try {
            $product = $this->productService->createProduct(
                $validated,
                $request->file('image')
            );

            return redirect()->route('admin.products.show', $product->id)
                ->with('success', "Produk <strong>{$product->name}</strong> berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $product = $this->productService->getProduct($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit(int $id)
    {
        $product    = $this->productService->getProduct($id);
        $categories = $this->categoryService->getAllCategories(true);
        $suppliers  = $this->supplierService->getAllSuppliers(true);
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, int $id)
    {
        if (!$request->filled('code')) {
            $request->merge([
                'code' => 'PRD-' . strtoupper(\Illuminate\Support\Str::random(8))
            ]);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'code'        => "required|string|max:100|unique:products,sku,{$id}",
            'description' => 'nullable|string',
            'buy_price'   => 'required|numeric|min:0',
            'sell_price'  => 'required|numeric|min:0',
            'min_stock'   => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'attributes'  => 'nullable|array',
            'attributes.*.attribute_name'  => 'required_with:attributes|string|max:100',
            'attributes.*.attribute_value' => 'required_with:attributes|string|max:255',
        ]);

        try {
            $product = $this->productService->updateProduct(
                $id,
                $validated,
                $request->file('image')
            );

            return redirect()->route('admin.products.show', $product->id)
                ->with('success', "Produk <strong>{$product->name}</strong> berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->productService->deleteProduct($id);
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return back()->with('error', 'Produk tidak dapat dihapus karena masih terhubung dengan data riwayat transaksi stok.');
            }
            return back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
