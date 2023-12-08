<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PurchaseAllExport implements FromView
{

    protected $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function view(): View
    {
        $purchases = $this->purchases;
        return view('export.excel.purchase_all', get_defined_vars());
    }
}
