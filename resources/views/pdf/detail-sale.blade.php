<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle de Venta</title>
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
            Detalle de Venta
        </div>
        <div class="products">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Descuento</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->product->name ?? 'PRODUCTO BORRADO' }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>{{ $sale->price }}</td>
                            <td>{{ $sale->discount }}</td>
                            <td>{{ $sale->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
