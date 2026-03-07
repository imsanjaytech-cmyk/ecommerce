<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;

class AdminController extends Controller {
    public function adminDashboard()
    {
        if (
            Auth::user()->role !== 'admin' ||
            !in_array(Auth::user()->email, ['gruba@gmail.com', 'imsanjay.tech@gmail.com'])
        ) {
            abort(403, 'Unauthorized access.');
        }
        $totalOrders    = Order::count();
        $totalProducts  = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue   = Order::where('status', 'delivered')->sum('total_amount');

        $featuredProducts = Product::with('category')
            ->published()
            ->featured()
            ->latest()
            ->take(5)
            ->get();

        $topProducts = Product::published()
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue',
            'featuredProducts',
            'topProducts',
            'recentOrders',
        ));
    }
}





