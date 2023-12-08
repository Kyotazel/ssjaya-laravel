<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PurchaseNotPaidExport implements FromView
{
    protected $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function view(): View
    {
        $purchases = $this->purchases;
        return view('export.excel.purchase_not_paid', get_defined_vars());
    }
}
