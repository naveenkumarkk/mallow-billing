<?php

namespace App\Services;

use App\Models\CustomerPurchaseInfo;
use App\Models\Denomination;
use Illuminate\Support\Carbon;
use App\Jobs\SendInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
class BillingService
{
    public static function calculateInitialLedgerBalance()
    {
        // Get the latest purchase record
        $getLatestPurchase = CustomerPurchaseInfo::latest('bill_date')->first();

        $initialBalance = 0;

        // Check if the latest purchase exists and compare the bill_date with today
        if (!$getLatestPurchase || Carbon::parse($getLatestPurchase->bill_date)->lt(Carbon::today())) {
            // Sum the value of all denominations
            $initialBalance = Denomination::sum('value');
        } else {
            // Use the after_ledger_balance from the latest purchase
            $initialBalance = $getLatestPurchase->after_purc_ledger_balance;
        }

        return $initialBalance;
    }



    public static function calculateChangeDenominations($balance)
    {
        $denominationList = Denomination::active()->get();

        $denominationBalanceCount = [];

        foreach ($denominationList as $denomination) {
            if ($balance >= $denomination->value && $denomination->count > 0) {
                $denominationCount = intdiv($balance, $denomination->value);
                $denominationBalanceCount[$denomination->id] = [
                    'id' => $denomination->id,
                    'name' => $denomination->name,
                    'value' => $denominationCount
                ];

                // Decrement the count of the denomination
                $denomination->count -= $denominationCount;
                $denomination->save();

                // Refresh the model to get the updated count
                $denomination->refresh();

                // Update the balance
                $balance -= $denominationCount * $denomination->value;
            }
        }

        return $denominationBalanceCount;
    }

    public static function sendEmail($invoiceDetails)
    {
        $pdf = Pdf::loadView('pdf.invoice', ['invoiceDetails' => $invoiceDetails]);
        $pdfPath = storage_path('app/public/invoice.pdf');
        $pdf->save($pdfPath);
      
        SendInvoice::dispatch($invoiceDetails['customerEmail'], $invoiceDetails, $pdfPath);
    }
}
