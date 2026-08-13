<?php

namespace App\Services\Categories;

use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\Service;
use App\Repositories\Categories\CategoriesRepository;
use App\Models\Categories;

class CategoriesServiceImplement extends Service implements CategoriesService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(CategoriesRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }
    
    /**
     * getCategories
     *
     * @param $categoriesid
     * @return mixed
     */
    public function getCategories(int $categoriesid)
    {
        try {
            return $this->mainRepository->getCategories($categoriesid);
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
            return null;
        }
    }

    public function getAllCategories(bool $activeOnly = false)
    {
        return Categories::orderBy('name')->get();
    }

    public function getAllCategoriesPaginated(int $perPage = 15, array $filters = [])
    {
        $query = Categories::with(['products' => function($q) use ($filters) {
            if (!empty($filters['search_product'])) {
                $q->where('name', 'like', '%' . $filters['search_product'] . '%');
            }
        }])->orderBy('name');
        
        if (!empty($filters['search_category'])) {
            $query->where('name', 'like', '%' . $filters['search_category'] . '%');
        }
        
        if (!empty($filters['search_product'])) {
            $query->whereHas('products', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search_product'] . '%');
            });
        }
        
        return $query->paginate($perPage)->appends($filters);
    }

    public function getCategory(int $id)
    {
        return Categories::findOrFail($id);
    }

    public function createCategory(array $data)
    {
        return Categories::create($data);
    }

    public function updateCategory(int $id, array $data)
    {
        $item = Categories::findOrFail($id);
        $item->update($data);

        return $item->refresh();
    }

    public function deleteCategory(int $id): bool
    {
        return (bool) Categories::findOrFail($id)->delete();
    }
}
