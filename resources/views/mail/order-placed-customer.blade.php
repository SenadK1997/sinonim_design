@php
    use App\Support\Money;
@endphp
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Potvrda narudžbe</title>
    <style>
        body { font-family: Georgia, "Times New Roman", serif; background: #faf7f3; color: #1a1a1a; margin: 0; padding: 24px; }
        .card { max-width: 560px; margin: 0 auto; background: #fff; border: 1px solid #e6d9c8; padding: 40px 32px; }
        .brand { text-align: center; font-size: 24px; letter-spacing: 1px; margin: 0 0 32px; }
        h1 { font-size: 32px; text-align: center; margin: 0 0 6px; font-weight: 400; letter-spacing: -0.01em; }
        .lead { text-align: center; color: #7d6045; margin: 0 0 8px; font-size: 15px; }
        .order-number { text-align: center; font-family: -apple-system, sans-serif; font-size: 12px; letter-spacing: 3px; text-transform: uppercase; color: #1a1a1a; margin: 24px 0; padding: 12px; border: 1px solid #d3bfa4; }
        .section { margin-top: 24px; padding-top: 20px; border-top: 1px solid #f2ece3; font-size: 14px; font-family: -apple-system, sans-serif; }
        .section h2 { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #7d6045; margin: 0 0 10px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 6px 0; vertical-align: top; }
        table td:last-child { text-align: right; }
        .totals td { border-top: 1px solid #e6d9c8; padding-top: 10px; }
        .totals .total { font-weight: 700; font-size: 16px; padding-top: 14px; }
        .footer { margin-top: 32px; padding-top: 20px; border-top: 1px solid #f2ece3; text-align: center; font-size: 12px; color: #7d6045; }
    </style>
</head>
<body>
    <div class="card">
        <p class="brand">{{ $brand }}</p>

        <h1>Hvala Vam na narudžbi</h1>
        <p class="lead">Zaprimili smo Vašu narudžbu i uskoro ćemo Vas kontaktirati.</p>

        <div class="order-number">Broj narudžbe: {{ $order->order_number }}</div>

        <div class="section">
            <h2>Stavke</h2>
            <table>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <span style="color:#7d6045;">
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
                <tr><td>Dostava</td><td>{{ $order->shipping_cost > 0 ? Money::format($order->shipping_cost) : 'BESPLATNO' }}</td></tr>
                <tr class="total"><td>UKUPNO</td><td>{{ Money::format($order->total) }}</td></tr>
            </table>
            <p style="margin-top:12px;color:#7d6045;font-size:13px;">Plaćanje pouzećem — plaćate prilikom preuzimanja pošiljke.</p>
        </div>

        <div class="section">
            <h2>Adresa za dostavu</h2>
            <p style="margin:0;">{{ $order->customer_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_city }}@if($order->shipping_postal_code), {{ $order->shipping_postal_code }}@endif</p>
        </div>

        <div class="footer">
            <p>Za sva pitanja o narudžbi:</p>
            @if($contactEmail)<p>✉️ {{ $contactEmail }}</p>@endif
            @if($whatsapp)<p>📱 WhatsApp: {{ $whatsapp }}</p>@endif
            <p style="margin-top:16px;">{{ $brand }} · Ručno rađeno s ljubavlju</p>
        </div>
    </div>
</body>
</html>
