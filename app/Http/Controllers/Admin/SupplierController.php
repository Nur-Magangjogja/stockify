<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Suppilers\SuppilersService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(protected SuppilersService $supplierService) {}

    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliersPaginated(15);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:100',
            'website'        => 'nullable|url|max:255',
            'notes'          => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        try {
            $supplier = $this->supplierService->createSupplier($validated);
            return redirect()->route('admin.suppliers.index')
                ->with('success', "Supplier <strong>{$supplier->name}</strong> berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $supplier = $this->supplierService->getSupplier($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:100',
            'website'        => 'nullable|url|max:255',
            'notes'          => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        try {
            $supplier = $this->supplierService->updateSupplier($id, $validated);
            return redirect()->route('admin.suppliers.index')
                ->with('success', "Supplier <strong>{$supplier->name}</strong> berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->supplierService->deleteSupplier($id);
            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
