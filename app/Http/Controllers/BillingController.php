<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPurchaseInfo;
use App\Models\Denomination;
use App\Models\DenominationLog;
use App\Models\Product;
use App\Models\PurchaseLog;
use App\Services\BillingService;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;



class BillingController extends Controller
{
    public function index()
    {
        if (!Cache::has('denomination_list')) {
            $denominationList = Cache::remember('denomination_list', now()->addHours(24), function () {
                return Denomination::active()->get();
            });

            foreach ($denominationList as $denomination) {
                Cache::put('denomination_' . $denomination->id, $denomination, now()->addHours(24));
            }
        }
        $denominations = Cache::get('denomination_list');

        return view('costing', compact('denominations'));
    }

    public function searchProduct(Request $request)
    {
        $validator = $validator = Validator::make($request->all(), [
            'product' => 'required'
        ], [
            'product.required' => 'You must add at least one character!'
        ]);

        if ($validator->fails()) {
            return response()->json(['Validation fail', $validator->errors()->messages()], 400);
        }

        if (!Cache::has('product_list')) {

            $productList = Cache::remember('product_list', now()->addHour(), function () {
                return Product::active()->get();
            });

            foreach ($productList as $product) {
                Cache::put('product_' . $productList->id, $productList, now()->addHours(24));
            }
        }

        $product = Cache::get('product_list');

        $productName = trim($request->input('product'));
        $filteredData = $product->filter(function ($data) use ($productName) {
            return stripos($data->name, $productName) !== false;
        });

        return response()->json($filteredData->values());
    }

    public function storeAndGenerateBill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer-email' => 'required|email',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'amount_paid' => 'required|integer|min:1',
            'denominations' => 'required|array|min:1',
            'denominations.*.id' => 'required|integer|exists:denominations,id',
            'denominations.*.value' => 'required|integer'
        ], [
            'products.required' => 'You must add at least one product.',
            'products.min' => 'You must add at least one product.',
            'products.*.id.required' => 'Each product must have a valid ID.',
            'products.*.id.integer' => 'Product ID must be an integer.',
            'products.*.id.exists' => 'Product ID must exist in the database.',
            'products.*.quantity.required' => 'Each product must have a quantity specified.',
            'products.*.quantity.integer' => 'Product quantity must be an integer.',
            'products.*.quantity.min' => 'Product quantity must be at least 1.',
            'amount.required' => 'You must enter the customer paid amount',
            'amount.min' => 'You must add at least One Rupee.',
            'denominations.required' => 'You must add at least One Denomination.',
            'denominations.*.id.required' => 'Each Denomination must have a valid ID.',
            'denominations.*.count.required' => 'Each Denomination must have a valid Count.',
        ]);

        if ($validator->fails()) {
            return response()->json(['Validation fail', $validator->errors()->messages()], 400);
        }

        $purchaseList = $request->input('products');
        $denominationsList = $request->input('denominations');
        $paidAmount = (int) $request->input('amount_paid', 0);
        $totalPurchasedAmountWithOutTax =  $totalPurchasedAmountWithTax = $totalTaxAmount = 0;
        $customer = Customer::firstOrCreate(['email' => $request->input('customer-email')]);

        $sales = CustomerPurchaseInfo::create([
            'customer_id' => $customer->id,
            'bill_date' => now()->toDateString(),
            'paid_amount' => $paidAmount,
            'before_purc_ledger_balance' => BillingService::calculateInitialLedgerBalance(),
            'after_purc_ledger_balance' => 0
        ]);

        foreach ($purchaseList as $product) {

            $productDetails = Product::find($product['id']);
            $initialStock = $productDetails->available_stock;

            $productDetails->available_stock = $initialStock - (int) $product['quantity'];
            $productDetails->save();

            $currentProductPrice = (int) $product['quantity'] * $productDetails->selling_price_per_unit;
            $taxAmount = 0;
            if ($productDetails->tax_percentage > 0) {
                $taxAmount = ($productDetails->tax_percentage * $currentProductPrice) / 100;
            }
            $priceAfterTax = ($taxAmount + $currentProductPrice);

            $totalTaxAmount += $taxAmount;
            $totalPurchasedAmountWithOutTax += $currentProductPrice;
            $totalPurchasedAmountWithTax += $priceAfterTax;

            $productDetails->setAttribute('price_before_tax', $currentProductPrice);
            $productDetails->setAttribute('product_tax', $taxAmount);
            $productDetails->setAttribute('sold_quantity', $product['quantity']);
            $productDetails->setAttribute('price_after_tax', $priceAfterTax);

            $productInfo[] = $productDetails;

            PurchaseLog::create([
                'sales_id' => $sales->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'purchase_price_per_unit' => $productDetails->purchase_price_per_unit,
                'selling_price_per_unit' => $productDetails->selling_price_per_unit,
                'stock_before_purchase' => $initialStock,
                'stock_after_purchase' => $productDetails->available_stock,
                'tax_percentage' => $productDetails->tax_percentage,
                'price_without_tax' => $currentProductPrice,
                'price_with_tax' => $priceAfterTax
            ]);
        }

        foreach ($denominationsList as $denomination) {
            $denominationCount = Denomination::find($denomination['id']);
            // Add the Customer Given Denomination Count
            $denominationCount->count += (int) $denomination['value'];
            $denominationCount->save();

            DenominationLog::create([
                'sales_id' => $sales->id,
                'denomination_id' => $denomination['id'],
                'count' => $denomination['value']
            ]);
        }

        $ledgerAmount = Denomination::sum('value');
        $balance = $ledgerAmount - $paidAmount;

        $sales->after_purc_ledger_balance = $balance;
        $sales->save();

        $balanceToTheCustomer = floor($totalPurchasedAmountWithTax) - $paidAmount;
        if ($balanceToTheCustomer < 0) {
            $balanceToTheCustomer *= -1;
        }
        $calculateChange = BillingService::calculateChangeDenominations($balanceToTheCustomer);

        $billingPage = [
            'customerEmail' => $customer->email,
            'purchasedProducts' => $productInfo,
            'balanceToTheCustomer' => $balanceToTheCustomer,
            'denominationsChange' => $calculateChange,
            'denominationsAfterPurchase' => Denomination::active()->get(),
            'salesId' => $sales->id,
            'totalTaxAmount' => $totalTaxAmount,
            'totalPurchasedAmountWithOutTax' => $totalPurchasedAmountWithOutTax,
            'totalPurchasedAmountWithTax' => $totalPurchasedAmountWithTax,
            'roundedTotalPurchasedAmount' => floor($totalPurchasedAmountWithTax)
        ];

        return view('billing', $billingPage);
    }


    public function downloadInvoicePdf(Request $request)
    {
        $htmlContent = $request->input('html');
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);
        $dompdf->render();
        $dompdf->stream('invoice.pdf');
    }
}
