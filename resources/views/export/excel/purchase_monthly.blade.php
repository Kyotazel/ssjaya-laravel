<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="width: 24px; height: 70px; text-align: center; vertical-align: middle; border: 1px solid #000;">
            </th>
            <th
                style="width: 100px; height: 70px; text-align: center; vertical-align: middle; background-color: #9BC2E6; border: 1px solid #000;">
                <b>AREA</b>
            </th>
            <th
                style="width: 60px; height: 70px; text-align: center; vertical-align: middle; background-color: #9BC2E6; border: 1px solid #000;">
                <b>NOTA</b>
            </th>
            <th
                style="width: 95px; height: 70px; text-align: center; vertical-align: middle; background-color: #9BC2E6; border: 1px solid #000;">
                <b>TANGGAL</b>
            </th>
            <th
                style="width: 200px; height: 70px; text-align: center; vertical-align: middle; background-color: #9BC2E6; border: 1px solid #000;">
                <b>APOTEK</b>
            </th>
            @foreach ($products as $product)
                <th
                    style="width: 60px; height: 70px; text-align: center; vertical-align: middle; background-color: #DACB04; border: 1px solid #000; word-wrap: break-word;">
                    <b>{{ $product->nama }}</b>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($purchases as $purchase)
            <tr>
                <td style="border: 1px solid #000;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000;">{{ $purchase->pharmacy->city->nama ?? '' }}</td>
                <td style="border: 1px solid #000;">{{ $purchase->code }}</td>
                <td style="border: 1px solid #000;">{{ carbonParse($purchase->date)->format('d-m-Y') }}</td>
                <td style="border: 1px solid #000;word-wrap: break-word;">{{ $purchase->pharmacy->nama_apotek ?? '' }}
                </td>
                @foreach ($products as $product)
                    <td style="border: 1px solid #000; text-align: center; vertical-align: middle">
                        @foreach ($purchase->products as $purchaseProduct)
                            @if ($purchaseProduct->product_id == $product->id)
                                {{ $purchaseProduct->stock }}
                            @endif
                        @endforeach
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="border: 1px solid #000; text-align: center"><b>TOTAL</b></td>
            @foreach ($products as $product)
                <td style="border: 1px solid #000; text-align: center; vertical-align: middle">
                    {{-- sum the product --}}
                    {{ $purchases->sum(function ($purchase) use ($product) {
                        return $purchase->products->sum(function ($purchaseProduct) use ($product) {
                            if ($purchaseProduct->product_id == $product->id) {
                                return $purchaseProduct->stock;
                            }
                        });
                    }) }}
                </td>
            @endforeach
        </tr>
    </tfoot>
</table>
