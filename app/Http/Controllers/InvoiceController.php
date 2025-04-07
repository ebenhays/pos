<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Stock;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Helpers\CodeGenerator;
use App\Models\DailyTransaction;

class InvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $batch_no): View
    {
        $items = [];
        $records = DailyTransaction::where('batch_no', $batch_no)->get();
        $invoiceNumber = CodeGenerator::generateCode();
        $date = Carbon::now();
        foreach ($records as $record) {
            $itemName = $record->stock->item;
            $cashier = $record->user->name;
            $price = $record->item_amount;
            $qty = $record->qty_sold;
            $unit = $record->stock->item_unit_code;
            $total = $record->total_per_item;
            $custAmt = $record->amount_tendered;

            $items[] = [
                'itemName' => $itemName,
                'cashier' => $cashier,
                'price' => $price,
                'qty' => $qty,
                'unit' => $unit,
                'invoiceNumber' => $invoiceNumber,
                'date' => $date,
                'total' => $total,
                'custAmt' => $custAmt
            ];

        }
        return view('invoices.invoice', ['items' => $items]);

    }
}
