<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h3 {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        th {
            background: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>

<h3>Статистика поиска</h3>
<p>Период: {{ $from->format('Y-m-d') }} — {{ $to->format('Y-m-d') }}</p>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Запрос</th>
        <th>Количество</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->tag }}</td>
            <td>{{ $row->qty }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
