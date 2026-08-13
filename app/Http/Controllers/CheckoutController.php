<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedAdmin;
use App\Mail\OrderPlacedCustomer;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function show()
    {
        return view('checkout.index', [
            'shippingFlatRate' => (float) Setting::get('shipping_flat_rate', 5),
            'shippingFreeOver' => Setting::get('shipping_free_over')
                ? (float) Setting::get('shipping_free_over')
                : null,
            'shippingNote' => Setting::get('shipping_note'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:180'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:80'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
            'shipping_country' => ['nullable', 'string', 'max:5'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'cart_items' => ['required', 'json'],
        ], [
            'customer_name.required' => 'Molimo unesite ime i prezime.',
            'customer_phone.required' => 'Molimo unesite broj telefona.',
            'shipping_address.required' => 'Molimo unesite adresu za dostavu.',
            'shipping_city.required' => 'Molimo unesite grad.',
            'cart_items.required' => 'Vaša korpa je prazna.',
        ]);

        $cartItems = json_decode($data['cart_items'], true) ?: [];
        if (empty($cartItems)) {
            return back()->withInput()->withErrors(['cart_items' => 'Vaša korpa je prazna.']);
        }

        try {
            $order = DB::transaction(function () use ($data, $cartItems) {
                // Resolve line items server-side (never trust client prices)
                $lines = [];
                $subtotal = 0;

                foreach ($cartItems as $ci) {
                    $productId = (int) ($ci['product_id'] ?? 0);
                    $variantId = ! empty($ci['variant_id']) ? (int) $ci['variant_id'] : null;
                    $qty = max(1, (int) ($ci['qty'] ?? 1));

                    $product = Product::find($productId);
                    if (! $product) {
                        continue;
                    }

                    $variant = $variantId ? ProductVariant::where('product_id', $productId)->find($variantId) : null;

                    $unitPrice = $variant ? $variant->price() : $product->effectivePrice();
                    $lineTotal = round($unitPrice * $qty, 2);
                    $subtotal += $lineTotal;

                    $lines[] = [
                        'product' => $product,
                        'variant' => $variant,
                        'qty' => $qty,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                        'size' => $variant?->size ?? ($ci['size'] ?? null),
                        'color' => $variant?->color ?? ($ci['color'] ?? null),
                    ];
                }

                if (empty($lines)) {
                    abort(422, 'Nijedan proizvod iz korpe nije pronađen u katalogu.');
                }

                // Shipping
                $flat = (float) Setting::get('shipping_flat_rate', 5);
                $freeOver = Setting::get('shipping_free_over');
                $freeOver = $freeOver !== null && $freeOver !== '' ? (float) $freeOver : null;
                $shipping = ($freeOver !== null && $subtotal >= $freeOver) ? 0.0 : $flat;

                $total = round($subtotal + $shipping, 2);

                // Customer — reuse if phone matches, else create
                $phoneNormalized = preg_replace('/\s+/', '', $data['customer_phone']);
                $customer = Customer::where('phone', $phoneNormalized)->first()
                    ?? Customer::create([
                        'name' => $data['customer_name'],
                        'phone' => $phoneNormalized,
                        'email' => $data['customer_email'] ?? null,
                        'address' => $data['shipping_address'],
                        'city' => $data['shipping_city'],
                        'postal_code' => $data['shipping_postal_code'] ?? null,
                        'country' => $data['shipping_country'] ?? 'BA',
                    ]);

                // Update customer info if newer data was provided
                $customer->fill([
                    'name' => $data['customer_name'],
                    'email' => $data['customer_email'] ?? $customer->email,
                    'address' => $data['shipping_address'],
                    'city' => $data['shipping_city'],
                    'postal_code' => $data['shipping_postal_code'] ?? $customer->postal_code,
                ])->save();

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'customer_id' => $customer->id,
                    'status' => Order::STATUS_PENDING,
                    'source' => Order::SOURCE_WEB,
                    'customer_name' => $data['customer_name'],
                    'customer_email' => $data['customer_email'] ?? null,
                    'customer_phone' => $phoneNormalized,
                    'shipping_address' => $data['shipping_address'],
                    'shipping_city' => $data['shipping_city'],
                    'shipping_postal_code' => $data['shipping_postal_code'] ?? null,
                    'shipping_country' => $data['shipping_country'] ?? 'BA',
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shipping,
                    'discount_amount' => 0,
                    'total' => $total,
                    'currency' => 'BAM',
                    'notes' => $data['notes'] ?? null,
                    'payment_method' => 'cod',
                ]);

                $overSold = [];
                foreach ($lines as $line) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $line['product']->id,
                        'product_variant_id' => $line['variant']?->id,
                        'product_name' => $line['product']->name,
                        'size' => $line['size'],
                        'color' => $line['color'],
                        'quantity' => $line['qty'],
                        'unit_price' => $line['unit_price'],
                        'line_total' => $line['line_total'],
                    ]);

                    // Decrement stock if we have a variant; don't block on shortage
                    if ($line['variant']) {
                        $currentStock = (int) $line['variant']->stock;
                        if ($currentStock >= $line['qty']) {
                            $line['variant']->decrement('stock', $line['qty']);
                        } else {
                            $overSold[] = $line['product']->name . ' (' . $line['size'] . '/' . $line['color'] . ')';
                            if ($currentStock > 0) {
                                $line['variant']->update(['stock' => 0]);
                            }
                        }
                    }
                }

                if (! empty($overSold)) {
                    $order->update([
                        'admin_notes' => 'PAŽNJA — narudžba prelazi zalihe za: ' . implode(', ', $overSold)
                            . '. Provjeri s kupcem prije potvrde.',
                    ]);
                }

                return $order;
            });
        } catch (\Throwable $e) {
            Log::error('Checkout failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return back()->withInput()->withErrors([
                'cart_items' => 'Nešto je pošlo po zlu prilikom obrade narudžbe. Molimo pokušajte ponovo ili nas kontaktirajte.',
            ]);
        }

        // Send notification emails (silent-fail so a mail issue doesn't break checkout)
        try {
            $adminEmail = Setting::get('contact_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new OrderPlacedAdmin($order));
            }
            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(new OrderPlacedCustomer($order));
            }
        } catch (\Throwable $e) {
            Log::warning('Order email send failed', ['order' => $order->order_number, 'error' => $e->getMessage()]);
        }

        return redirect()->route('checkout.success', $order->order_number)
            ->with('just_placed', true);
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items'])
            ->firstOrFail();

        return view('checkout.success', [
            'order' => $order,
            'justPlaced' => session('just_placed', false),
        ]);
    }
}
