<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 80mm;
        }

        .receipt {
            text-align: center;
        }

        .header {
            font-size: 14px;
            font-weight: bold;
        }

        .products {
            margin-top: 10px;
        }

        .products table {
            width: 100%;
            border-collapse: collapse;
        }

        .products table th,
        .products table td {
            border: 1px solid black;
            padding: 4px;
        }

        .total {
            margin-top: 10px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <p>Nombre de la Tienda</p>
            <p>Dirección de la Tienda</p>
            <p>Teléfono de la Tienda</p>
        </div>
        <div class="products">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['quantitySell'] }}</td>
                            <td>{{ $product['sale_price'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="total">
            <p>Total: {{ $totalAmount['total'] }}</p>
        </div>
    </div>
</body>

</html>
