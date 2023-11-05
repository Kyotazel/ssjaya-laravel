<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Rekap Nota Belum Lunas {{ $namedStart }} - {{ $namedEnd }}</title>
</head>

<body>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>

    <h1>Nota Belum Lunas {{ $namedStart }} - {{ $namedEnd }}</h1>

    <table>
        <thead>
            <tr>
                <th style="border: 1px solid black;">Nomor Nota</th>
                <th style="border: 1px solid black;">Apotek</th>
                <th style="border: 1px solid black;">Sales</th>
                <th style="border: 1px solid black;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr>
                    <td style="border: 1px solid black;">{{ $purchase->code }}</td>
                    <td style="border: 1px solid black;">{{ $purchase->pharmacy->nama_apotek ?? '' }}</td>
                    <td style="border: 1px solid black;">{{ $purchase->sales->nama }}</td>
                    <td style="border: 1px solid black;">{{ carbonParse($purchase->date)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
