<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            /* font-family: 'terminus','Courier New', Courier, monospace; */
            font-family: 'terminus';
            width: 280px; /* ~80mm */
            font-size: 13px;
            margin: auto;
            padding: 0;
        }
        .bold{
            font-weight: bold
        }
        .center {
            text-align: center;
        }
        .dashed {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            overflow:wrap;
        }
        table th{
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
        }
        td {
            padding: 2px 2px;
        }
        th{
            text-align: left
        }
        .total {
            font-weight: bold;
            padding-right: 150px;
        }
    </style>

</head>
<body>
    <div class="center">
        <h2>DTEE COLD STORE</h2>
        <p>Dealers in all kinds of food stuff</p>
        <strong>INVOICE</strong><br>
        Invoice Number: #{{ $items[0]['invoiceNumber'] }}<br>
        Date: {{ $items[0]['date'] }}<br>
        Cashier: {{$items[0]['cashier']}}
    </div>

    <div class="dashed"></div>

    <table>
        <thead>
            <tr class="bold">
                <td>Item</td>
                <td class="amount-right">Cost</td>
                <td>Qty</td>
                <td>Total</td>
                
            </tr>
        </thead>
        <tbody>
            @php 
            $i = 0; 
            $total_price = 0;
            //$total_tax = [];
            $grand_total = 0;
        @endphp
            @foreach($items as $item)
            @php $i++; @endphp
            <tr>
                <td>{{ $i }}.{{ $item['itemName'] }}</td>
                <td>{{ number_format($item['price'],2) }}</td>
                <td>{{ $item['qty'] }}</td>
                <td>{{ $item['total'] }}</td>
               
                @php 
                        $tax = 0;
                        $item_total = $item['price'] * $item['qty'];
                        $tax_amount = ($item_total * $tax) / 100;
                        $item_total_with_tax = $item_total + $tax_amount;
                        $total_price += $item_total;
                        $total_tax = 0;
                        $grand_total += $item_total_with_tax;
                    @endphp
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed"></div>

    <table>
        <tr>
            <td class="total">Total</td>
            <td></td>
            <td></td>
            <td class="total">{{ number_format($total_price, 2) }}</td>
            </tr>
            <tr>
            <td class="total">Paid</td>
            <td></td>
            <td></td>
            <td class="total">{{ number_format( $items[0]['custAmt'], 2) }}</td>
            </tr>
            <tr>
            <td class="total">Change</td>
            <td></td>
            <td></td>
            <td class="total">{{ number_format( $items[0]['custAmt'] - $total_price, 2)  }}</td>
            </tr>
        </tr>
    </table>

    <div class="dashed"></div>

    <div class="center">
        <p>Thank you for your purchase!</p>
    </div>
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
