<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'string',
            'price' => 'required',
            'image' => 'required|file|image|max:2048',
        ]);

        $formFields['image'] = $request->file('image')->store('productImages', 'public');
        return Product::create($formFields);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        $product->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'price' => $request->input('price')
        ]);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Product::destroy($id);
    }
    
    /**
     * Search for a a product name
     */
    public function search(string $name)
    {
        return Product::where('name', 'like', '%'.$name.'%')->get();
    }
}
