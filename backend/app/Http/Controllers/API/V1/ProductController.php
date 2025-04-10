<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $result = Product::with('user', 'category')
            ->where('user_id', auth()->user()->id)
            ->get();
        return ResponseFormatter::success($result, 'Product Fetched Successfully', 200);
    }

    public function all()
    {
        $result = Product::with('user', 'category')
            ->get();
        return ResponseFormatter::success($result, 'Product Fetched Successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return ResponseFormatter::validationError($validator->errors());
        }
        try {
            $data = request()->only('name', 'description', 'price', 'category_id');
            $data['image'] = request()->file('image')->store('product', 'public');
            $data['user_id'] = auth()->user()->id;
            $data['slug'] = \Str::slug(request('name'));
            $data['status'] = 1;
            $product = Product::create($data);
            return ResponseFormatter::success($product, 'Product Created Successfully', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::with('user', 'category')->find($id);
            if (!$product)
                return ResponseFormatter::error(null, 'Product Not Found', 404);
            return ResponseFormatter::success($product, 'Product Fetched Successfully', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return ResponseFormatter::validationError($validator->errors());
        }
        try {
            $data = request()->only('name', 'description', 'price', 'category_id', 'status');
            if (request()->file('image')) {
                $product = Product::find(request('id'));
                if ($product->image)
                    Storage::disk('public')->delete($product->image);
            }
            $data['slug'] = \Str::slug(request('name'));
            $product = Product::create($data);
            return ResponseFormatter::success($product, 'Product updated Successfully', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);
            if (!$product)
                return ResponseFormatter::error(null, 'Product Not Found', 404);

            if ($product->image)
                Storage::disk('public')->delete($product->image);
            $product->delete();
            return ResponseFormatter::success(null, 'Product Deleted Successfully', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }
}
