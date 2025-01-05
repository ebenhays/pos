<?php

namespace App\Filament\Resources\DailyTransactionResource\Pages;

use Carbon\Carbon;
use App\Models\Stock;
use Filament\Actions;
use App\Helpers\CodeGenerator;
use App\Models\DailyTransaction;
use App\Models\GovTaxTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DailyTransactionResource;

class CreateDailyTransaction extends CreateRecord
{
    protected static string $resource = DailyTransactionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        //save record
        $batchNo = CodeGenerator::generateCode();
        $savedRecords = [];
        DB::transaction(function () use ($data, $batchNo, &$savedRecords) {
            foreach ($data['sales_info'] as $item) {
                $savedRecords[] = DailyTransaction::create([
                    'batch_no' => $batchNo,
                    'transaction_date' => now(),
                    'stock_id' => $item["item_stock"],
                    'payment_type_id' => $data["payment_type"],
                    'item_amount' => $item["item_price"],
                    'amount_tendered' => $data["customer_amount"],
                    'discount' => $data['discount'],
                    'total_tax' => $data['total_taxes'],
                    'qty_sold' => $item['qty'],
                    'selling_code' => $item['item_unit'],
                    'user_id' => auth()->id()
                ]);
            }
            //save tax info if any
            if (!empty($data['Taxes'])) {
                foreach ($data["Taxes"] as $tax) {
                    GovTaxTransaction::create([
                        'batch_no' => $batchNo,
                        'tax_name' => $tax['name'],
                        'percentage' => $tax['percentage'],
                        'total' => $tax['total_tax']
                    ]);
                }
            }

        });
        return end($savedRecords);

    }
}
