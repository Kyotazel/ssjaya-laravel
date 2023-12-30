<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseMonthlyReport implements WithMultipleSheets
{

    private $salesId, $year, $start_month, $end_month;
    public function __construct($salesId, $year, $start_month, $end_month)
    {
        $this->salesId = $salesId;
        $this->year = $year;
        $this->start_month = $start_month;
        $this->end_month = $end_month;
    }

    public function sheets(): array
    {
        $sheets = [];

        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        foreach (range($this->start_month, $this->end_month) as $month) {

            $purchases = Purchase::query()
                ->with(['pharmacy.city', 'products'])
                ->where('sales_id', $this->salesId)
                ->whereYear('date', $this->year)
                ->whereMonth('date', $month)
                ->orderBy('code', 'asc')
                ->get();

            $title = $months[$month - 1] . ' ' . $this->year;
            $sheets[] = new PurchaseMonthlyReportSheet($purchases, $title);
        }

        return $sheets;
    }
}

class PurchaseMonthlyReportSheet implements FromView, WithEvents
{

    private $purchases, $title;

    public function __construct($purchases, $title)
    {
        $this->purchases = $purchases;
        $this->title = $title;
    }

    public function view(): View
    {

        $products = Product::get();

        return view('export.excel.purchase_monthly', [
            'purchases' => $this->purchases,
            'products' => $products
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set freeze pane for the sheet
                $this->setFreezePane($event->sheet);

                // Set title for the sheet
                $event->sheet->setTitle($this->title);
            },
        ];
    }

    private function setFreezePane($sheet)
    {
        // Set freeze pane for the sheet
        $sheet->freezePane('A2');
    }
}
