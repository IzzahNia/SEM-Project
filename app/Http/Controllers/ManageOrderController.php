<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ManageOrderController extends Controller
{
    // public function showOrderList()
    // {
    //     $orders = Order::with(['orderItems', 'user'])->get();
    //     return view('ManageOrder.order_list', compact('orders'));
    // }

    public function showOrderList()
    {
        $user = Auth::user();

        // Check user role
        if ($user->role === 'admin') {
            // Admin sees all orders
            $orders = Order::with(['orderItems', 'user'])->get();
        } else {
            // Other users see only their orders
            $orders = Order::with(['orderItems', 'user'])->where('user_id', $user->id)->get();
        }

        return view('ManageOrder.order_list', compact('orders'));
    }

    public function showOrderProgress()
    {
        // Assuming you want the latest order for the currently authenticated user
        $order = Order::with(['orderItems', 'user'])
            ->where('user_id', auth()->id())  // Filter by the authenticated user
            ->latest()  // Get the most recent order
            ->first();  // Get the first result (the latest order)
    
        // Pass the order to the view
        return view('ManageOrder.order_progress', compact('order'));
    }
    

    // Method to retrieve product price via AJAX
    public function findPrice(Request $request)
    {
        $product = Product::find($request->id);
        if ($product) {
            return response()->json(['product_selling_price' => $product->product_selling_price]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function addOrder()
    {
        $users = User::all();
        $products = Product::all();


        return view('ManageOrder.add_order_form', compact('users', 'products'));
    }
    
    public function createOrder(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'order_datetime' => 'required|date',
            'order_status' => 'required|string',
            'product_id.*' => 'required|exists:products,id',
            'qty.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric',
            'dis.*' => 'nullable|numeric|min:0|max:100',
            'amount.*' => 'required|numeric',
            'payment_type' => 'required|string',
        ]);
    
        // Check stock availability for each product in the order
        foreach ($request->product_id as $index => $productId) {
            $product = Product::find($productId);
            $orderQuantity = $request->qty[$index];
    
            if ($orderQuantity > $product->product_quantity) {
                return redirect()->back()->with('error', "Order quantity for product {$product->product_name} exceeds available stock.");
            }
        }
    
        $validatedData['order_total_price'] = array_sum($request->amount);
    
        $order = Order::create([
            'user_id' => $validatedData['customer_id'],
            'order_datetime' => $validatedData['order_datetime'],
            'order_status' => $validatedData['order_status'],
            'order_total_price' => $validatedData['order_total_price'],
        ]);
    
        foreach ($request->product_id as $index => $productId) {
            $order->orderItems()->create([
                'product_id' => $productId,
                'order_item_quantity' => $request->qty[$index],
                'order_item_price' => $request->price[$index],
                'order_item_discount' => $request->dis[$index] ?? 0,
                'order_item_amount' => $request->amount[$index],
            ]);
    
            // Deduct product quantity and update product_sold if status is Completed
            if ($validatedData['order_status'] === 'Completed') {
                $product = Product::find($productId);
                $product->decrement('product_quantity', $request->qty[$index]); // Deduct quantity
                $product->increment('product_sold', $request->qty[$index]); // Add to sold count
            }
        }
    
        $paymentStatus = ($validatedData['order_status'] === 'Completed') ? 'Paid' : 'Pending';
    
        $order->payment()->create([
            'payment_amount' => $validatedData['order_total_price'],
            'payment_status' => $paymentStatus,
            'payment_type' => $validatedData['payment_type'],
        ]);
    
        return redirect()->route('order.list')->with('success', 'Order created successfully.');
    }
    
    
    public function deleteOrder($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
    
        DB::beginTransaction();
    
        try {
            // Delete the associated payment record
            $order->payment()->delete();
    
            // Delete all associated order items
            $order->orderItems()->delete();
    
            // Delete the order itself
            $order->delete();
    
            DB::commit(); // Commit the transaction
    
            return redirect()->route('order.list')->with('success', 'Order and related payment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback(); // Rollback the transaction if something goes wrong
            return redirect()->route('order.list')->with('error', 'Failed to delete order and related payment.');
        }
    }
    
    public function viewOrder($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        return view('ManageOrder.view_order_form', compact('order', 'id'));
    }

    public function editOrder($id)
    {
        $order = Order::with('orderItems.product', 'payment')->findOrFail($id);
        $products = Product::all();
        return view('ManageOrder.edit_order_form', compact('order', 'products'));
    }

    // public function updateOrder(Request $request, $id)
    // {
    //     $order = Order::with('orderItems')->findOrFail($id);
    
    //     $validatedData = $request->validate([
    //         'order_datetime' => 'required|date',
    //         'order_status' => 'required|string',
    //         'product_id.*' => 'required|exists:products,id',
    //         'qty.*' => 'required|integer|min:1',
    //         'price.*' => 'required|numeric',
    //         'dis.*' => 'nullable|numeric|min:0|max:100',
    //         'amount.*' => 'required|numeric',
    //         'payment_type' => 'required|string',
    //     ]);

    //     foreach ($request->product_id as $index => $productId) {
    //         $product = Product::find($productId);
    //         $orderQuantity = $request->qty[$index];
    
    //         if ($orderQuantity > $product->product_quantity) {
    //             return redirect()->back()->with('error', "Order quantity for product {$product->product_name} exceeds available stock.");
    //         }
    //     }
    
    //     DB::beginTransaction();
    //     try {
    //         // Reverse changes to product quantities and sold count if the original status was Completed
    //         if ($order->order_status === 'Completed') {
    //             foreach ($order->orderItems as $item) {
    //                 $product = Product::find($item->product_id);
    //                 $product->increment('product_quantity', $item->order_item_quantity); // Revert quantity
    //                 $product->decrement('product_sold', $item->order_item_quantity); // Revert sold count
    //             }
    //         }
    
    //         $order->update([
    //             'order_datetime' => $validatedData['order_datetime'],
    //             'order_status' => $validatedData['order_status'],
    //             'order_total_price' => array_sum($request->amount),
    //         ]);
    
    //         $order->orderItems()->delete(); // Remove old items
    
    //         foreach ($request->product_id as $index => $productId) {
    //             $order->orderItems()->create([
    //                 'product_id' => $productId,
    //                 'order_item_quantity' => $request->qty[$index],
    //                 'order_item_price' => $request->price[$index],
    //                 'order_item_discount' => $request->dis[$index] ?? 0,
    //                 'order_item_amount' => $request->amount[$index],
    //             ]);
    
    //             // Deduct product quantity and update product_sold if status is Completed
    //             if ($validatedData['order_status'] === 'Completed') {
    //                 $product = Product::find($productId);
    //                 $product->decrement('product_quantity', $request->qty[$index]); // Deduct quantity
    //                 $product->increment('product_sold', $request->qty[$index]); // Add to sold count
    //             }
    //         }
    
    //         // Update or create payment record
    //         $order->payment()->updateOrCreate(
    //             [],
    //             [
    //                 'payment_amount' => $order->order_total_price,
    //                 'payment_status' => $validatedData['order_status'] === 'Completed' ? 'Paid' : 'Pending',
    //                 'payment_type' => $validatedData['payment_type'],
    //             ]
    //         );
    
    //         DB::commit();
    //         return redirect()->route('order.list')->with('success', 'Order updated successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->route('order.list')->with('error', 'Failed to update order.');
    //     }
    // }
     
    public function updateOrder(Request $request, $id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
    
        $validatedData = $request->validate([
            'order_datetime' => 'required|date',
            'order_status' => 'required|string',
            'product_id.*' => 'required|exists:products,id',
            'qty.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric',
            'dis.*' => 'nullable|numeric|min:0|max:100',
            'amount.*' => 'required|numeric',
            'payment_type' => 'required|string',
        ]);
        
        // Validate quantities against available stock
        foreach ($request->product_id as $index => $productId) {
            $product = Product::find($productId);
            $orderedQuantity = $request->qty[$index];

            if ($orderedQuantity > $product->product_quantity) {
                // If quantity exceeds available stock, throw an error
                return redirect()->back()->with('error', 'Order quantity for product ' . $product->product_name . ' exceeds available stock. Available stock: ' . $product->product_quantity);
            }
        }
        DB::beginTransaction();
        try {
            // Reverse changes to product quantities and sold count if the original status was Completed
            if ($order->order_status === 'Completed') {
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    $product->increment('product_quantity', $item->order_item_quantity); // Revert quantity
                    $product->decrement('product_sold', $item->order_item_quantity); // Revert sold count
                }
            }
    
    
            $order->update([
                'order_datetime' => $validatedData['order_datetime'],
                'order_status' => $validatedData['order_status'],
                'order_total_price' => array_sum($request->amount),
            ]);
    
            $order->orderItems()->delete(); // Remove old items
    
            foreach ($request->product_id as $index => $productId) {
                $order->orderItems()->create([
                    'product_id' => $productId,
                    'order_item_quantity' => $request->qty[$index],
                    'order_item_price' => $request->price[$index],
                    'order_item_discount' => $request->dis[$index] ?? 0,
                    'order_item_amount' => $request->amount[$index],
                ]);
    
                // Deduct product quantity and update product_sold if status is Completed
                if ($validatedData['order_status'] === 'Completed') {
                    $product = Product::find($productId);
                    $product->decrement('product_quantity', $request->qty[$index]); // Deduct quantity
                    $product->increment('product_sold', $request->qty[$index]); // Add to sold count
                }
            }
    
            // Update or create payment record
            $order->payment()->updateOrCreate(
                [],
                [
                    'payment_amount' => $order->order_total_price,
                    'payment_status' => $validatedData['order_status'] === 'Completed' ? 'Paid' : 'Pending',
                    'payment_type' => $validatedData['payment_type'],
                ]
            );
    
            DB::commit();
            return redirect()->route('order.list')->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('order.list')->with('error', 'Failed to update order.');
        }
    }
    

}
