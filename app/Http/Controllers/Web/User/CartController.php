<?php

namespace App\Http\Controllers\Web\User;

use App\Actions\Modules\User\Cart\CreateAction;
use App\Actions\Modules\User\Cart\DeleteAction;
use App\Actions\Modules\User\Cart\GetListingAction;
use App\Actions\Modules\User\Cart\UpdateAction;
use App\Actions\Models\Order\CustomActions as OrderCustomActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public static function index()
    {
        $user = Auth::user();
        $cartItems = GetListingAction::execute($user);
        $cartItemsSubtotal = $cartItems->pluck('subtotal')->toArray();

        $subtotal = OrderCustomActions::calculateSubtotal($cartItemsSubtotal);
        $roundingAdjustment = 0; // OrderCustomActions::calculateRoundingAdjustment($cartItemsSubtotal); // NOTE: missed out in migrations
        $discountAmount = 0; // $request['discount_amount] // NOTE: not within scope
        $grandTotal = OrderCustomActions::calculateGrandTotal($subtotal, $roundingAdjustment, $discountAmount);

        $props = [];
        $props['cartItems'] = $cartItems;
        $props['subtotal'] = number_format($subtotal, 2);
        $props['roundingAdjustment'] = number_format($roundingAdjustment, 2);
        $props['discountAmount'] = number_format($discountAmount, 2);
        $props['grandTotal'] = number_format($grandTotal, 2);

        return Inertia::render('user/cart/Index', $props);
    }

    public static function create($id)
    {
        abort(404);
    }

    public static function store(CartRequest $request)
    {
        $user = Auth::user();
        CreateAction::execute($user, $request->validated());

        return redirect()->back();
    }

    public static function show($id)
    {
        abort(404);
    }

    public static function edit($id)
    {
        abort(404);
    }

    public static function update(CartRequest $request, $id)
    {
        UpdateAction::execute($id, $request->validated());

        return redirect()->back();
    }

    public static function destroy($id)
    {
        DeleteAction::execute($id);

        return redirect()->back();
    }
}
