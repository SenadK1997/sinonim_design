@php
    use App\Support\Money;
@endphp
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nova narudžba</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #faf7f3; color: #1a1a1a; margin: 0; padding: 24px; }
        .card { max-width: 640px; margin: 0 auto; background: #fff; border: 1px solid #e6d9c8; padding: 32px; }
        h1 { font-size: 28px; margin: 0 0 8px; font-weight: 500; }
        .muted { color: #7d6045; font-size: 13px; margin: 0; }
        .section { margin-top: 28px; padding-top: 20px; border-top: 1px solid #f2ece3; }
        .section h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: #7d6045; margin: 0 0 12px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        table td { padding: 8px 0; vertical-align: top; }
        table td:last-child { text-align: right; }
        .totals td { border-top: 1px solid #e6d9c8; padding-top: 12px; }
        .totals .total { font-weight: 700; font-size: 18px; padding-top: 16px; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 24px; background: #1a1a1a; color: #fff !important; text-decoration: none; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="card">
        <p class="muted" style="text-transform:uppercase;letter-spacing:2px;margin-bottom:6px;">Nova narudžba</p>
        <h1>{{ $order->order_number }}</h1>
        <p class="muted">{{ $order->created_at->format('d.m.Y H:i') }} · Izvor: web</p>

        <div class="section">
            <h2>Kupac</h2>
            <p style="margin:0 0 4px;"><strong>{{ $order->customer_name }}</strong></p>
            <p style="margin:0 0 4px;">📞 <a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></p>
            @if($order->customer_email)
                <p style="margin:0 0 4px;">✉️ <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></p>
            @endif
            <p style="margin:12px 0 0;">{{ $order->shipping_address }}, {{ $order->shipping_city }} @if($order->shipping_postal_code) · {{ $order->shipping_postal_code }} @endif</p>
        </div>

        <div class="section">
            <h2>Stavke</h2>
            <table>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <span class="muted">
                                @if($item->size) {{ $item->size }} @endif
                                @if($item->color) · {{ $item->color }} @endif
                                · × {{ $item->quantity }}
                            </span>
                        </td>
                        <td>{{ Money::format($item->line_total) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="section">
            <table class="totals">
                <tr><td>Međuzbir</td><td>{{ Money::format($order->subtotal) }}</td></tr>
                <tr><td>Dostava</td><td>{{ Money::format($order->shipping_cost) }}</td></tr>
                <tr class="total"><td>UKUPNO</td><td>{{ Money::format($order->total) }}</td></tr>
            </table>
            <p class="muted" style="margin-top:12px;">Plaćanje pouzećem.</p>
        </div>

        @if($order->notes)
            <div class="section">
                <h2>Napomena kupca</h2>
                <p style="margin:0;">{{ $order->notes }}</p>
            </div>
        @endif

        @if($order->admin_notes)
            <div class="section" style="background:#fef3f2;border-left:4px solid #dc2626;padding-left:16px;">
                <h2 style="color:#dc2626;">Interna napomena</h2>
                <p style="margin:0;color:#b91c1c;">{{ $order->admin_notes }}</p>
            </div>
        @endif

        <div class="section">
            <a href="{{ url('/admin/orders/' . $order->id . '/edit') }}" class="btn">Otvori u admin panelu →</a>
        </div>
    </div>
</body>
</html>
