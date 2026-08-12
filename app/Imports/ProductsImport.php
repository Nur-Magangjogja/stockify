<?php

namespace App\Imports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Products([
            'name'           => $row['nama_produk'],
            'sku'            => $row['sku'],
            'category_id'    => $row['id_kategori'],
            'supplier_id'    => $row['id_supplier'],
            'description'    => $row['deskripsi'] ?? null,
            'purchase_price' => $row['harga_beli'],
            'selling_price'  => $row['harga_jual'],
            'minimum_stock'  => $row['minimum_stok'] ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_produk' => 'required|string|max:255',
            'sku'         => 'required|string|max:100|unique:products,sku',
            'id_kategori' => 'required|exists:categories,id',
            'id_supplier' => 'required|exists:suppliers,id',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
        ];
    }
}
