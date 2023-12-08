<table>
    <thead>
        <tr>
            <th style="width: 120px">Nomor Nota</th>
            <th style="width: 200px">Apotek</th>
            <th style="width: 100px">Sales</th>
            <th style="width: 120px">Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchases as $purchase)
            <tr>
                <td>{{ $purchase->code }}</td>
                <td>{{ $purchase->pharmacy->nama_apotek ?? '' }}</td>
                <td>{{ $purchase->sales->nama }}</td>
                <td>{{ carbonParse($purchase->date)->format('d M Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
