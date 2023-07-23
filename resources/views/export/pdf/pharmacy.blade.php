<html>

<head>
</head>

<body>
    <h2 style="text-align: center;">Data Apotek Tanggal {{ date('d M Y') }} </h2>
    <br>
    <table style="width: 40%;">
        <tr>
            <th style="width: 32%;">Provinsi</th>
            <td>:</td>
            <td>{{ $data_provinsi }}</td>
        </tr>
        <tr>
            <th>Kota</th>
            <td>:</td>
            <td>{{ $data_kota }}</td>
        </tr>
        <tr>
            <th>Sales</th>
            <td>:</td>
            <td>{{ $data_sales }}</td>
        </tr>
        <tr>
            <th>Produk</th>
            <td>:</td>
            <td>{{ $data_product }}</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
        <tr>
            <th style="width: 5%; border: 1px solid black; border-collapse: collapse; text-align: center; padding: 4px;">
                No</th>
            <th
                style="width: 15%; border: 1px solid black; border-collapse: collapse; text-align: center; padding: 4px;">
                Nama</th>
            <th
                style="width: 25%; border: 1px solid black; border-collapse: collapse; text-align: center; padding: 4px;">
                Alamat</th>
            <th
                style="width: 15%; border: 1px solid black; border-collapse: collapse; text-align: center; padding: 4px;">
                Kota</th>
            <th
                style="width: 15%; border: 1px solid black; border-collapse: collapse; text-align: center; padding: 4px;">
                Sales</th>
            <th
                style="width: 25%; border: 1px solid black; border-collapse: collapse; text-align: center; padding: 4px;">
                Produk</th>
        </tr>
        @foreach ($pharmacies as $pharmacy)
            <tr>
                <td style="width: 5%; border: 1px solid black; border-collapse: collapse; padding: 4px;">
                    {{ $loop->iteration }}
                </td>
                <td style="width: 15%; border: 1px solid black; border-collapse: collapse; padding: 4px;">
                    <?= $pharmacy->nama_apotek ?></td>
                <td style="width: 25%; border: 1px solid black; border-collapse: collapse; padding: 4px;">
                    <?= $pharmacy->alamat ?></td>
                <td style="width: 15%; border: 1px solid black; border-collapse: collapse; padding: 4px;">
                    <?= $pharmacy->nama_kota ?></td>
                <td style="width: 15%; border: 1px solid black; border-collapse: collapse; padding: 4px;">
                    <?= $pharmacy->nama_sales ?></td>
                <td style="width: 25%; border: 1px solid black; border-collapse: collapse; padding: 4px;">
                    <?= $pharmacy->produk ?></td>
            </tr>
        @endforeach
    </table>
</body>

</html>
