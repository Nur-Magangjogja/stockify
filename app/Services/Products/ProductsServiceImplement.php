<?php

namespace App\Services\Products;

use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\Service;
use App\Repositories\Products\ProductsRepository;
use App\Models\Products;

class ProductsServiceImplement extends Service implements ProductsService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(ProductsRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }
    
    /**
     * getProducts
     *
     * @param $productid
     * @return mixed
     */
    public function getProducts($productid)
    {
      try {
        return $this->mainRepository->getProducts($productid);
      } catch (\Exception $exception) {
        Log::debug($exception->getMessage());
        return [];
      }
    }

    public function getAllProducts(array $filters = [], int $perPage = 15)
    {
      $query = Products::query()->with(['category', 'supplier']);
      foreach (['category_id', 'supplier_id'] as $field) {
        if (array_key_exists($field, $filters) && $filters[$field] !== null && $filters[$field] !== '') {
          $query->where($field, $filters[$field]);
        }
      }
      if (!empty($filters['search'])) {
        $query->where(fn ($q) => $q->where('name', 'like', '%' . $filters['search'] . '%')->orWhere('sku', 'like', '%' . $filters['search'] . '%'));
      }
      if (($filters['stock_status'] ?? '') === 'low') {
        $query->lowStock();
      } elseif (($filters['stock_status'] ?? '') === 'out') {
        $query->whereRaw(
          "COALESCE((SELECT SUM(CASE WHEN status = 'Diterima' AND type = 'Masuk' THEN quantity WHEN status = 'Diterima' AND type = 'Keluar' THEN -quantity ELSE 0 END) FROM stock_transactions WHERE stock_transactions.product_id = products.id), 0) <= 0"
        );
      }
      return $query->orderBy($filters['sort_by'] ?? 'name', $filters['sort_dir'] ?? 'asc')->paginate($perPage)->appends($filters);
    }

    public function getLowStockProducts(int $limit = 8)
    {
      return Products::with('stockTransactions')->orderBy('minimum_stock', 'desc')->limit($limit)->get();
    }

    public function getProduct(int $id)
    {
      return Products::with(['category', 'supplier', 'attributes', 'stockTransactions'])->findOrFail($id);
    }

    public function createProduct(array $data, $image = null)
    {
      $normalizedData = $this->normalize($data);
      
      if ($image) {
          $normalizedData['image'] = $image->store('products', 'public');
      }
      
      return Products::create($normalizedData);
    }

    public function updateProduct(int $id, array $data, $image = null)
    {
      $product = Products::findOrFail($id);
      $normalizedData = $this->normalize($data);

      if ($image) {
          if ($product->image) {
              \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
          }
          $normalizedData['image'] = $image->store('products', 'public');
      }

      $product->update($normalizedData);

      return $product->refresh();
    }

    public function deleteProduct(int $id): bool
    {
      $product = Products::findOrFail($id);
      if ($product->image) {
          \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
      }
      return (bool) $product->delete();
    }

    private function normalize(array $data): array
    {
      $data['sku'] = $data['sku'] ?? $data['code'] ?? null;
      $data['purchase_price'] = $data['purchase_price'] ?? $data['buy_price'] ?? 0;
      $data['selling_price'] = $data['selling_price'] ?? $data['sell_price'] ?? 0;
      $data['minimum_stock'] = $data['minimum_stock'] ?? $data['min_stock'] ?? 0;

      return array_intersect_key($data, array_flip(['category_id', 'supplier_id', 'name', 'sku', 'description', 'purchase_price', 'selling_price', 'image', 'minimum_stock']));
    }
}
