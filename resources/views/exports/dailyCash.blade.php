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
            <th>Dinero Inicial</th>
            <th>Dinero Final</th>
            <th>Ganancia</th>
            <th>Encargado</th>
            <th>Fecha de Apertura</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dailyCashes as $cash)
            <tr>
                <td>{{ $cash->start_money }}</td>
                <td>{{ $cash->final_money }}</td>
                <td>{{ $cash->profit }}</td>
                <td>{{ isset($cash->user) ? $cash->user->sur_name . ' ' . $cash->user->name ?? 'USUARIO ELIMINADO' : 'USUARIO ELIMINADO' }}
                </td>
                <td>{{ $cash->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
