<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .card-header {
            text-align: center;
            background-color: lightblue;
            padding: 8px;
        }
        .container {
            padding: 16px;
        }
        bold {
            color: Tomato;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Billing Page
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Customer Email: {{ $invoiceDetails['customerEmail'] }}</label>
                </div>
                <h3>Bill Section</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Purchase Price</th>
                            <th>Tax Percentage</th>
                            <th>Tax Payable</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoiceDetails['purchasedProducts'] as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->selling_price_per_unit }}</td>
                            <td>{{ $product->sold_quantity }}</td>
                            <td>{{ number_format($product->price_before_tax, 2) }}</td>
                            <td>{{ $product->tax_percentage }}%</td>
                            <td>{{ number_format($product->product_tax, 2) }}</td>
                            <td>{{ number_format($product->price_after_tax, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Total Price Without Tax: {{ $invoiceDetails['totalPurchasedAmountWithOutTax'] }}</label>
                        </div>
                        <div class="col-md-6">
                            <label>Total Tax Payable: {{ $invoiceDetails['totalTaxAmount'] }}</label>
                        </div>
                        <div class="col-md-6">
                            <label>Net Price of the purchased Product: {{ $invoiceDetails['totalPurchasedAmountWithTax'] }}</label>
                        </div>
                        <div class="col-md-6">
                            <label>Rounded Down Value of the purchased items net price: {{ $invoiceDetails['roundedTotalPurchasedAmount'] }}</label>
                        </div>
                        <div class="col-md-6">
                            <label>Balance Payable to the customer: {{ $invoiceDetails['balanceToTheCustomer'] }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
