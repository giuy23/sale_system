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
            width: 80mm !important;
            max-width: 80mm !important;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        hr {
            color: #000000;
        }

        .name-company h1,
        .type-ballot h1 {
            text-align: center;
            font-size: 16px;
            margin: 2px;
            font-weight: bolder;
        }

        .name-company h2,
        .type-ballot h2 {
            text-align: center;
            font-size: 12px;
            margin: 2px;

        }

        .client-info h2 {
            font-size: 14px;
            margin: 4px;
        }

        .date {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            width: 80mm !important;
            padding: 10px 0px;
        }

        .date h3 {
            font-size: 12px;
            display: inline;
            padding: 0px 20px;
        }

        .info-total h2 {
            font-size: 14px;
            margin: 4px;
            text-align: right;
        }

        .text-footer h3,
        .congrats h3 {
            font-size: 12px;
            text-align: center;
            margin: 4px;
        }

        .header {
            font-size: 14px;
            font-weight: bold;
        }

        .products table {
            width: 100%;
            border-collapse: collapse;
        }

        .products table th,
        .products table td {
            border: 0px;
            font-size: 11px;
            padding: 2px;
        }

        .total {
            margin-top: 10px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
@php
    $typeSell = 'EFECTIVO';
    if ($sale->state == 2) {
        $typeSell = 'ANULADO';
    } elseif ($sale->state == 3) {
        $typeSell = 'CRÉDITO';
    } elseif ($sale->state == 4) {
        $typeSell = 'CRÉDITO - PAGADO';
    }
@endphp

<body>

    <div class="receipt">
        <div class="name-company">
            <h1>{{ $enterprise->name_comercial }}</h1>
            <h2>RUC: {{ $enterprise->RUC }} </h2>
            <h2>DIRECCIÓN: {{ $enterprise->address }} </h2>
            <h2>TELÉFONO: {{ $enterprise->cell_phone }} </h2>

        </div>
        <hr>
        <img src="{{ public_path('img/layouts/image-enterprise.jpg') }}">
        <div class="type-ballot">
            <h1>BOLETA DE VENTA ELETRÓNICA</h1>
            <h1>B0001 - 0001235</h1>
        </div>

        <hr>

        <div class="client-info">
            <h2>CLIENTE: {{ $sale->client->full_name }}</h2>
            <h2>DNI: {{ $sale->client->document_number }}</h2>
        </div>

        <nav class="date">
            <h3>FECHA: {{ date('Y-m-d', strtotime($sale->created_at)) }}</h3>
            <h3>HORA: {{ date('H:i:s', strtotime($sale->created_at)) }}</h3>
        </nav>
        <hr>

        <div class="products">
            <table>
                <thead>
                    <tr>
                        <th>Cant.</th>
                        <th>Producto</th>
                        <th>P. U.</th>
                        <th>Desc.</th>
                        <th>Tot.</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- @foreach ($sales as $product)
                        <tr>
                            <td>{{ $product->quantitySell }}</td>
                            <td>{{ $product->name }}</td>
                            <td>S/ {{ $product->sale_price }}</td>
                            <td>S/ {{ $product->discount }}</td>
                            <td>S/ {{ $product->total }}</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>

        <hr>

        <nav class="info-total">
            <h2>Total Gravado: S/ {{ $sale->sub_total }}</h2>
            <h2> IGV: S/ {{ $sale->igv }} </h2>
            <h2>Total: S/ {{ $sale->total }}</h2>
        </nav>

        <hr>

        <nav class="footer">
            <main class="text-footer">
                <h3> <strong>SON:</strong> {{ $sale->id }} </h3>
                <h3> <strong>FORMA DE PAGO: {{ $typeSell }}</strong>
                </h3>
            </main>
            <main class="qr">
                <img src="" alt="">
            </main>
            <main class="congrats">
                <h3>---Gracias por su Preferencia---</h3>
            </main>
        </nav>
    </div>
</body>

</html>

