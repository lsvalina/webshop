<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Order;
use App\Models\PricingModifier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request, Order $order) {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'products' => 'required|array',
            'products.*.SKU' => 'required|exists:products,SKU',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $user = auth('sanctum')->user();

            $order->first_name = $request->input('first_name');
            $order->last_name = $request->input('last_name');
            $order->email = $request->input('email');
            $order->phone_number = $request->input('phone_number');
            $order->address = $request->input('address');
            $order->city = $request->input('city');
            $order->country_id = $request->input('country_id');

            $order->user_id= $user?->id;

            $products = $request->input('products');
            $country = Country::findOrFail($order->country_id);
            $subtotalPrice = 0;
            $totalVat = 0;
            $productDetails = [];
            foreach ($products as $productData) {
                $product = Product::with('categories')->find($productData['SKU']);
                $calculatedPrice = $product->calculatePrice($user);
                $vatPercentage = $product->calculateVatPercentage($country);
                $productDetails[] = [
                    'SKU' => $productData['SKU'],
                    'price' => $calculatedPrice,
                    'vat_percentage' => $vatPercentage,
                    'quantity' => $productData['quantity'],
                ];
                $subtotalPrice += $calculatedPrice * $productData['quantity'];
                $totalVat += $calculatedPrice * $productData['quantity'] * $vatPercentage;
            }

            $order->subtotal = $subtotalPrice;

            $totalPrice =  $subtotalPrice + $totalVat;

            $appliedModifiers = [];
            $orderModifiers = PricingModifier::where('scope', 'order')->get();
            foreach ($orderModifiers as $modifier) {
                if ($modifier->isApplicable($order)) {
                    $appliedModifiers[] = $modifier->id;
                    $totalPrice = $modifier->apply($totalPrice);
                }
            }
            $order->total = $totalPrice;

            $order->save();

            $order->products()->attach($productDetails);
            $order->pricingModifiers()->attach($appliedModifiers);
            DB::commit();

            return response()->json($order, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['error' => 'Order creation failed.'], 500);
        }
    }
}
