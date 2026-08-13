<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Categories\CategoriesService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoriesService $categoryService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search_category', 'search_product']);
        $categories = $this->categoryService->getAllCategoriesPaginated(15, $filters);
        return view('admin.categories.index', compact('categories', 'filters'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        try {
            $category = $this->categoryService->createCategory($validated);
            return redirect()->route('admin.categories.index')
                ->with('success', "Kategori <strong>{$category->name}</strong> berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $category = $this->categoryService->getCategory($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'        => "required|string|max:255|unique:categories,name,{$id}",
            'description' => 'nullable|string',
        ]);

        try {
            $category = $this->categoryService->updateCategory($id, $validated);
            return redirect()->route('admin.categories.index')
                ->with('success', "Kategori <strong>{$category->name}</strong> berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
