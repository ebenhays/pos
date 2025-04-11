<?php

namespace App\Filament\Resources\DailyTransactionResource\Pages;

use App\Models\Stock;
use App\Enum\StockUnitEnum;
use App\Models\ProductStock;
use App\Helpers\CodeGenerator;
use App\Models\DailyTransaction;
use App\Models\GovTaxTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyTransactionSummary;
use Illuminate\Database\Eloquent\Model;
use App\Models\SalesOnCreditTransactions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DailyTransactionResource;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CreateDailyTransaction extends CreateRecord
{
    protected static string $resource = DailyTransactionResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function handleRecordCreation(array $data): Model
    {
        //save record
        $batchNo = CodeGenerator::generateCode();
        $savedRecords = [];
        DB::transaction(function () use ($data, $batchNo, &$savedRecords) {
            foreach ($data['sales_info'] as $item) {
                if ($data["customer_amount"] < $data['amount_due']) {
                    throw new BadRequestException('Customer Amount is lesser');
                }

                $stock = ProductStock::firstWhere('id', $item["item_stock"]);
                if ($stock->item_qty_remaining - $item['qty'] < 0) {
                    throw new BadRequestException("{$stock->item} is low in quantity. just left with {$stock->item_qty_remaining}");
                }
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
                    'user_id' => auth()->id(),
                    'total_sales' => $data['amount_due']
                ]);
                $stock->item_qty_remaining -= $item['qty'];
                $stock->save();

                if ($data["payment_type"] === "4") {
                    SalesOnCreditTransactions::create([
                        'batch_no' => $batchNo,
                        'customer_id' => $data['customer']
                    ]);
                }

                //save daily summaries
                $totalPrice = (float) $item["item_price"] * (float) $item['qty'];
                $wholesaleSales = $item['item_unit'] === StockUnitEnum::WHOLESALE->value ? $totalPrice : 0;
                $retailSales = $item['item_unit'] === StockUnitEnum::RETAIL->value ? $totalPrice : 0;
                $salesInBox = $item['item_unit'] === StockUnitEnum::BOX->value ? $totalPrice : 0;
                $salesInKilos = $item['item_unit'] === StockUnitEnum::KILOS->value ? $totalPrice : 0;
                $totalWholeSalesQtySold = $this->getProductUnitQtySold(StockUnitEnum::WHOLESALE->value, (int) $item["item_stock"]);
                $totalRetailSalesQtySold = $this->getProductUnitQtySold(StockUnitEnum::RETAIL->value, (int) $item["item_stock"]);
                $totalBoxSalesQtySold = $this->getProductUnitQtySold(StockUnitEnum::BOX->value, (int) $item["item_stock"]);
                $totalKilosSalesQtySold = $this->getProductUnitQtySold(StockUnitEnum::KILOS->value, (int) $item["item_stock"]);
                $COS_wholesale = $item['item_unit'] === StockUnitEnum::WHOLESALE->value ?
                    $totalWholeSalesQtySold * $stock->item_cost_price : 0;
                $COS_retail = $item['item_unit'] === StockUnitEnum::RETAIL->value ?
                    $totalRetailSalesQtySold * $stock->item_cost_price : 0;
                $COS_box = $item['item_unit'] === StockUnitEnum::BOX->value ?
                    $totalBoxSalesQtySold * $stock->item_cost_price_per_box : 0;
                $COS_kilos = $item['item_unit'] === StockUnitEnum::KILOS->value ?
                    $totalKilosSalesQtySold * $stock->item_cost_price_per_kg : 0;

                $dailyTransactionSummary =
                    DailyTransactionSummary::where('stock_id', $item["item_stock"])
                        ->whereDate('transaction_date', today())
                        ->first();
                if (!$dailyTransactionSummary) {
                    DailyTransactionSummary::create([
                        'transaction_date' => now(),
                        'stock_id' => $item["item_stock"],
                        'total_wholesale_sales' => $wholesaleSales,
                        'total_retail_sales' => $retailSales,
                        'total_sales_in_box' => $salesInBox,
                        'total_sales_in_kilos' => $salesInKilos,
                        'COS_wholesale' => $COS_wholesale,
                        'COS_retail' => $COS_retail,
                        'COS_box' => $COS_box,
                        'COS_kilos' => $COS_kilos,
                    ]);
                } else {
                    $dailyTransactionSummary->total_wholesale_sales += $wholesaleSales;
                    $dailyTransactionSummary->total_retail_sales += $retailSales;
                    $dailyTransactionSummary->total_sales_in_box += $salesInBox;
                    $dailyTransactionSummary->total_sales_in_kilos += $salesInKilos;
                    $dailyTransactionSummary->COS_wholesale = $COS_wholesale;
                    $dailyTransactionSummary->COS_retail = $COS_retail;
                    $dailyTransactionSummary->COS_box = $COS_box;
                    $dailyTransactionSummary->COS_kilos = $COS_kilos;
                    $dailyTransactionSummary->save();
                }
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

    private function getProductUnitQtySold(string $unit, int $stockId): float
    {
        return DailyTransaction::where('stock_id', $stockId)
            ->where('selling_code', $unit)
            ->whereDate('transaction_date', now())
            ->sum('qty_sold');

    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('create daily sales');
    }
}
