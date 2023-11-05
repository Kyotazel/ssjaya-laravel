<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Nota {{ $purchase->code }}</title>

    <style>
        #table-list {
            border-collapse: collapse;
            width: 100%;
        }

        #table-list th,
        #table-list td {
            text-align: left;
            padding: 8px;
            border: 1px solid black;
        }

        #table-list th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        #table-list tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1>Detail Nota {{ $purchase->code }}</h1>
    <hr>

    <table style="width: 50%">
        <tr>
            <td><b>Nomor Nota</b></td>
            <td>:</td>
            <td>{{ $purchase->code }}</td>
        </tr>
        <tr>
            <td><b>Nama Apotek</b></td>
            <td>:</td>
            <td>{{ $purchase->pharmacy->nama_apotek ?? '' }}</td>
        </tr>
        <tr>
            <td><b>Nama Sales</b></td>
            <td>:</td>
            <td>{{ $purchase->sales->nama }}</td>
        </tr>
        <tr>
            <td><b>Area</b></td>
            <td>:</td>
            <td>{{ $purchase->pharmacy->city->nama ?? '' }}</td>
        </tr>
        <tr>
            <td><b>Tanggal</b></td>
            <td>:</td>
            <td>{{ carbonParse($purchase->date)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><b>Status</b></td>
            <td>:</td>
            <td>{{ $purchase->status }}</td>
        </tr>
    </table>

    <h3>List Barang : </h3>
    <table id="table-list">
        <thead>
            <tr>
                <th style="border: 1px solid black;">Nama Produk</th>
                <th style="border: 1px solid black;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->products as $product)
                <tr>
                    <td style="border: 1px solid black;">{{ $product->product->nama }}</td>
                    <td style="border: 1px solid black;">{{ $product->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
