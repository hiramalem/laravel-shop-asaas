<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    

    public function all()
    {
        return Product::get();
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function existsBy(array $conditions): bool
    {
        return Product::where($conditions)->exists();
    }

    public function findBy(array $conditions)
    {
        $query = Product::query();

        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                $query->where(...$value);
            } else {
                $query->where($key, $value);
            }
        }

        return $query->firstOrFail();
    }
}