<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);

        $user = auth()->user();

        return view('purchase.create', compact('item','user'));
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchase.address', compact('item'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        session([
            'purchase_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        return redirect()->route('purchases.create', $item_id);
    }

    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // 二重購入防止
        if ($item->purchase) {
            return redirect('/')
                ->with('error', 'この商品はすでに購入されています');
        }

        //支払い方法取得
        $paymentMethod = $request->payment_method;
        $method = Purchase::PAYMENT_METHODS[$paymentMethod];

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => [$method],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            'success_url' => route('purchase.success', [
                'item_id' => $item_id,
                'paymentMethod' => $paymentMethod
            ]),

            'cancel_url' => route('purchase.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success($item_id, $paymentMethod)
    {
        $item = Item::findOrFail($item_id);

        // 二重購入防止
        if ($item->purchase) {
            return redirect('/')
                ->with('error', 'この商品はすでに購入されています');
        }

        $address = session('purchase_address', []);

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
            'postal_code' => $address['postal_code'] ?? auth()->user()->profile->postal_code,
            'address' => $address['address'] ?? auth()->user()->profile->address,
            'building' => $address['building'] ?? auth()->user()->profile->building,
        ]);

        $item->update([
            'is_sold' => true
        ]);

        return redirect('/')->with('success', '購入が完了しました');
    }

    public function cancel()
    {
        return redirect('/')
            ->with('error', '購入をキャンセルしました');
    }
}
