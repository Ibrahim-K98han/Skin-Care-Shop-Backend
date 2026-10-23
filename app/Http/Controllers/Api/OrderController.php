<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orders)
    {
    }

    // অর্ডার করা
    public function store(Request $request)
    {
        $data = $request->validate([
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $address = $request->user()->addresses()->findOrFail($data['address_id']);

        $order = $this->orders->placeFromCart($request->user(), $address, $data['note'] ?? null);

        return response()->json($order, 201);
    }

    // আমার সব অর্ডার
    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    // একটা অর্ডারের বিস্তারিত
    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return response()->json($order->load('items'));
    }
}
