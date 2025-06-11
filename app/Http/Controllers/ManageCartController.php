<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Banking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageCartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Ensure quantity is an integer and is at least 1
        $quantity = (int)$request->quantity;
    
        // Find the existing cart entry for the user and product
        $cart = Cart::where('user_id', auth()->id())
                    ->where('product_id', $request->product_id)
                    ->first();
    
        if ($cart) {
            // If the product exists, increment the quantity
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            // If the product doesn't exist in the cart, create a new entry
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $quantity,  // Set initial quantity to the passed value
            ]);
        }
    
        // return response()->json(['success' => true, 'message' => 'Product added to cart successfully!']);
        return redirect()->route('product')->with('success', 'Product added successfully.');
    }

    public function showCartList(Request $request)
    {
        // Assuming there's a relationship between the Cart and Product models
        $userId = auth()->id(); // Get the logged-in user's ID
        $carts = Cart::where('user_id', $userId)
            ->with('product') // Load associated product details
            ->get();
    
        // Pass the cart items to the view
        return view('ManageCart.cart_list', compact('carts'));
    }

    public function updateCartQuantity(Request $request)
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $cart = Cart::findOrFail($validated['cart_id']);
        $product = $cart->product;
    
        if ($validated['quantity'] > $product->product_quantity) {
            return response()->json(['status' => 'error', 'message' => 'Quantity exceeds stock availability'], 400);
        }
    
        $cart->quantity = $validated['quantity'];
        $cart->save();
    
        return response()->json(['status' => 'success', 'message' => 'Quantity updated']);
    }

    public function showCheckout(Request $request)
    {
        $validatedData = $request->validate([
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:carts,id',
        ]);

        $cartItems = Cart::whereIn('id', $validatedData['cart_ids'])->with('product')->get();

        $totalAmount = $cartItems->sum(function ($cart) {
            return $cart->product->product_selling_price * $cart->quantity;
        });

        // Store cart items and total amount in session
        session(['cartItems' => $cartItems, 'totalAmount' => $totalAmount]);

        return view('ManageCart.checkout', compact('cartItems', 'totalAmount'));
    }


    public function checkout(Request $request)
    {
        $validatedData = $request->validate([
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:carts,id',
        ]);
    
        $cartItems = Cart::whereIn('id', $validatedData['cart_ids'])->with('product')->get();
    
        // Calculate the total amount
        $totalAmount = $cartItems->sum(function ($cart) {
            return $cart->product->product_selling_price * $cart->quantity;
        });
        
       

        return view('ManageCart.checkout', compact('cartItems', 'totalAmount'));
    }
       
    public function placeOrder(Request $request)
    {
        // Proceed with order placement
        $totalAmount = session('totalAmount', 0); // Ensure the total amount is available

        if ($totalAmount <= 0) {
            return redirect()->route('cart.list')->with('error', 'Invalid total amount.');
        }

        $validatedData = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'cart_ids.*' => 'required',
            'qty.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
            'payment_type' => 'required|string',
            // 'total_amount' => 'required|numeric', // Ensure this field is validated
        ]);

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_datetime' => now(),
            'order_total_price' => $totalAmount,
            'order_status' => 'Pending',
        ]);

        // Add order items
        foreach ($request->product_id as $index => $productId) {
            $order->orderItems()->create([
                'product_id' => $productId,
                'order_item_quantity' => $request->qty[$index],
                'order_item_price' => $request->price[$index],
                'order_item_discount' => 0, // Add discounts if necessary
                'order_item_amount' => $request->amount[$index],
            ]);
        }


        // Create payment record
        $paymentStatus = $request->payment_type === 'Online Payment' ? 'Paid' : 'Pending';

        $order->payment()->create([
            'payment_amount' => $order->order_total_price,
            'payment_status' => $paymentStatus,
            'payment_type' => $request->payment_type,
        ]);

        // Delete selected cart items
        Cart::whereIn('id', $request->cart_ids)->delete();
        session()->forget(['cartItems', 'totalAmount']);

        if ($request->payment_type === 'Online Payment') {
            return redirect()->route('banking.form', ['order_id' => $order->id]);
        }

        return redirect()->route('order.progress')->with('success', 'Order placed successfully.');
    }

    public function destroy(Request $request)
    {
        $cartId = $request->input('cart_id');

        try {
            $cartItem = Cart::findOrFail($cartId);
            $cartItem->delete();

            return response()->json(['status' => 'success', 'message' => 'Cart item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete cart item.']);
        }
    }

    public function showBankingForm($order_id)
    {
        return view('banking.form', ['order_id' => $order_id]);
    }

    public function submitBankingForm(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'bank_name' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'reference_number' => 'required|string|max:255',

        ]);

        Banking::create([
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'bank_name' => $request->bank_name,
            'account_holder' => $request->account_holder,
            'reference_number' => $request->reference_number,
        ]);

        return redirect()->route('order.progress')->with('success', 'Payment completed successfully.');
    }

}
