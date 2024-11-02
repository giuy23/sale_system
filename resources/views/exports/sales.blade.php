<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    td {
        font-size: 14px;
    }
</style>

<table>
    <thead>
        <tr>
            <th>Subtotal</th>
            <th>Descuento</th>
            <th>Total</th>
            <th>IGV</th>
            <th>Estado</th>
            <th>Fecha de Creación</th>
            <th>Cliente</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
            @php
                $state = 'PAGADO';
                if ($sale->state == 2) {
                    $state = 'ANULADO';
                } elseif ($sale->state == 3) {
                    $state = 'CRÉDITO';
                } elseif ($sale->state == 4) {
                    $state = 'CRÉDITO PAGADO';
                }
            @endphp
            <tr>
                <td>{{ $sale->sub_total }}</td>
                <td>{{ $sale->discount_total }}</td>
                <td>{{ $sale->total }}</td>
                <td>{{ $sale->igv }}</td>
                <td>{{ $state }}</td>
                <td>{{ $sale->created_at }}</td>
                <td>{{ $sale->client->full_name ?? 'CLIENTE ELIMINADO' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
