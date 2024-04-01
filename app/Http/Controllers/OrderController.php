<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function simulatePayment(Order $order)
    {
        // Simulate random payment success or failure
        $paymentSuccess = (bool) random_int(0, 1);

        // Update order status based on payment success
        if ($paymentSuccess) {
            $order->update(['status' => 'SUCCESS']);
            return true;
        } else {
            $order->update(['status' => 'FAILED']);
            return false;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['shipping_address' => json_encode($request->input('shipping_address'))]);

        $fields = $request->validate([
            'invoice_number' => 'required|string|max:31|unique:orders,invoice_number',
            'shipping_address' => 'required|json',
            'products' => 'array', 
            'products.*.id' => 'integer|exists:products,id',
            'products.*.quantity' => 'integer|min:1',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'invoice_number' => $fields['invoice_number'],
            'shipping_address' => $fields['shipping_address']
        ]);

        
        foreach ($fields['products'] as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }

        $order->load('products');

        $order->total = $order->calculateTotal();
        $order->save();
        
        $paymentSuccess = $this->simulatePayment($order);

        if ($paymentSuccess) {
            return response()->json(['message' => 'Order created successfully and payment successful!', 'order' => $order->fresh()], 201);
        } else {
            return response()->json(['message' => 'Order created successfully but payment failed!', 'order' => $order->fresh()], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
