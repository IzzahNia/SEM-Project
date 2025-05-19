<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ManagePaymentController extends Controller
{
    public function showPaymentList(Request $request)
    {
        $user = Auth::user();
    
        // Check user role
        if ($user->role === 'admin') {
            $payments = Payment::with(['order.user'])->get();
        } else {
            $payments = Payment::with(['order.user'])->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }
    
        $groupBy = $request->input('group_by', 'month');
        $sortOrder = $request->input('sort_order', 'asc');
    
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
    
        // Use a raw SQL query to group and sort dynamically
        $revenueData = Order::where('order_status', 'completed')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('
                SUM((order_items.order_item_price - products.product_purchase_price) * order_items.order_item_quantity) as total_revenue,
                CASE 
                    WHEN ? = "day" THEN DATE(orders.created_at)
                    WHEN ? = "month" THEN DATE_FORMAT(orders.created_at, "%Y-%m")
                    WHEN ? = "year" THEN YEAR(orders.created_at)
                END as period
            ', [$groupBy, $groupBy, $groupBy])
            ->groupBy('period')
            ->orderBy('period', $sortOrder)
            ->get();
    
        // Check if the request is AJAX
        if ($request->ajax()) {
            return response()->json($revenueData);
        }
    
        return view('ManagePayment.payment_list', compact('payments', 'revenueData'));
    }    
    
    public function viewPayment($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        // $payment = Payment::with('orderItems')->findOrFail($id);
        return view('ManagePayment.view_payment_form', compact('order', 'id'));
    }

}
