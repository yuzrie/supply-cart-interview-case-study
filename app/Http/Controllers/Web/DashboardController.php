<?php

namespace App\Http\Controllers\Web;

use App\Actions\Modules\User\Product\GetListingAction as ProductGetListingAction;
use App\Actions\Modules\User\Order\GetListingAction as OrderGetListingAction;
use App\Actions\Modules\User\Cart\GetListingAction as CartGetListingAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public static function index()
    {
        $user = Auth::user();

        $productListingRequest = [
            'paginate' => 5,
            'orderBy' => 'id',
            'orderDirection' => 'desc',
        ];
        $products = ProductGetListingAction::execute($user, $productListingRequest);
        $orders = OrderGetListingAction::execute($user);
        $cartItems = CartGetListingAction::execute($user);

        $props = [];
        $props['products'] = $products;
        $props['countProducts'] = $products->total();
        $props['countCartItems'] = collect($cartItems->items())->sum('quantity');
        $props['countOrders'] = $orders->total();
        $props['sumOfGrandTotalOrders'] = number_format(collect($orders->items())->sum('grand_total'), 2);

        return Inertia::render('Dashboard', $props);
    }
}
