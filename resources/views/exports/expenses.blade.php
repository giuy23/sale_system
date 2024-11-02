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
            <th>Monto</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($expenses as $expense)
            @php
                $state = 'INGRESO';
                if ($expense->type == 2) {
                    $state = 'EGRESO';
                }
            @endphp
            <tr>
                <td>{{ $expense->amount }}</td>
                <td>{{ $expense->description ?? '' }}</td>
                <td>{{ $state }}</td>
                <td>{{ $expense->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
