<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RecycleActivity;
use App\Models\RedeemReward;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();

        if (Auth()->user()->hasRole('admin')) {
            // Admin sees all recycle activities
            $totalRecycleActivities = RecycleActivity::count();
            $totalRewards = RedeemReward::count();
            $recycleActivities = RecycleActivity::all();
            $totalOrder = Order::count();
        } else {
            // Regular user sees only their own recycle activities
            $totalRecycleActivities = RecycleActivity::where('user_id', Auth::id())->count();
            $totalRewards = RedeemReward::where('user_id', Auth::id())->count();
            $recycleActivities = RecycleActivity::where('user_id', Auth::id())->get();
            $totalOrder = Order::where('user_id', Auth::id())->count();
        }

        $chartProducts = Product::select('product_name', 'product_quantity')->get();
        
        $chartRecycleActivities = RecycleActivity::select('recycle_status', \DB::raw('count(*) as total'))
            ->when(!Auth::user()->hasRole('admin'), function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->groupBy('recycle_status')
            ->get();    
    
        $chartSalesProducts = OrderItem::select('products.product_name', \DB::raw('SUM(order_items.order_item_quantity) as total_quantity'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.order_status', 'completed')
            ->groupBy('products.product_name')
            ->get();

        //User purchase products
        $chartPurchaseProducts = OrderItem::select('products.product_name', \DB::raw('SUM(order_items.order_item_quantity) as purchase_quantity'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.order_status', 'completed')
            ->where('orders.user_id', Auth::id()) // Filter by logged-in user
            ->groupBy('products.product_name')
            ->get();

        // Monthly sales data
        $monthlySales = Order::where('order_status', 'completed')
            ->selectRaw('DATE_FORMAT(order_datetime, "%M %Y") as month, SUM(order_total_price) as total_sales')
            ->groupBy('month')
            ->orderByRaw('MIN(order_datetime)')
            ->get();

        return view('dashboard', compact('totalProducts','totalRecycleActivities', 'chartProducts', 'chartSalesProducts', 'chartRecycleActivities', 'totalRewards','totalOrder', 'monthlySales', 'chartPurchaseProducts'));
    }
}
