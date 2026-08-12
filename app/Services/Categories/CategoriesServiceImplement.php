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

    public function getAllCategoriesPaginated(int $perPage = 15)
    {
        return Categories::with('products')->orderBy('name')->paginate($perPage);
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
