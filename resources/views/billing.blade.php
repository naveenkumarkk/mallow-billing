<!DOCTYPE html>
<html>

<head>
    <title>Mallow Billing</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <div class="container mt-4 col-sm-10">
        <div class="card">
            <div class="card-header text-center font-weight-bold">
                Billing Page
            </div>
            <div class="card-body">


                <div class="form-group col-sm-4">
                    <label for="customer-email">Customer Email: <bold>{{$customerEmail}}</bold></label>

                </div>
                <div class="d-flex justify-content-center">
                    <h3 class="text-center" for="customer-email">Bill Section</h3>
                </div>

                <div id="product-table" class="container mt-4">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
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
                            @foreach ($purchasedProducts as $product)
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
                </div>


                <div class="container">
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <label>Total Price Without Tax: <bold>RS. {{$totalPurchasedAmountWithTax}}</bold></label>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <label>Total Tax Payable: <bold>RS. {{$totalTaxAmount}}</bold></label>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <label>Net Price of the purchased Product: <bold>RS. {{$totalPurchasedAmountWithTax}}</bold></label>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <label>Rounded Down Value of the purchased items net price: <bold>RS. {{$roundedTotalPurchasedAmount}}</bold></label>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <label>Balance Payable to the customer: <bold>RS. {{$balanceToTheCustomer}}</bold></label>
                        </div>
                    </div>
                </div>

                <div id='denominations_list'>
                    <h4>Balance Denominations To the Customer</h4>
                    @foreach ($denominationsChange as $denomination)
                    <div class="form-group col-sm-2">
                        <label for="denom-{{ $denomination['id'] }}">{{ $denomination['name'] }}</label>
                        <input type="number" id="denom-{{ $denomination['id'] }}" name="denominations[{{ $denomination['id'] }}]" class="form-control" value="{{ $denomination['value'] }}">
                    </div>
                    @endforeach


                    <h4>Available Denominations</h4>
                    @foreach ($denominationsAfterPurchase as $denomination)
                    <div class="form-group col-sm-2">
                        <label for="denom-{{ $denomination->name }}">{{ $denomination->name }}</label>
                        <input type="number" id="denom-{{ $denomination->value }}" name="denominations[{{ $denomination->value }}]" class="form-control" value="{{ $denomination->count }}">
                    </div>
                    @endforeach
                </div>
                <form id="pdf-form" method="post" action="{{ route('download.invoice') }}" style="display: none;">
                    @csrf
                    <input type="hidden" name="html" id="html-content">
                </form>
                <button id="download-btn" class="btn btn-success">Download Invoice</button>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('download-btn').addEventListener('click', function() {


        var clonedBody = document.body.cloneNode(true);

        // Remove the elements you want to exclude from the PDF
        var elementsToRemove = clonedBody.querySelectorAll('#denominations_list,#download-btn');
        elementsToRemove.forEach(function(element) {
            element.remove();
        });
        var styleElement = document.createElement('style');
        var css = `
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

            .card-header{
                text-align: center;
                background-color: lightblue;
                padding: 8px;
            }

            .container{
                padding: 16px;
            }

            bold{
                color:Tomato;
            }
        `;
        styleElement.textContent = css;
        clonedBody.insertBefore(styleElement, clonedBody.firstChild);
        var htmlContent = clonedBody.outerHTML;
        document.getElementById('html-content').value = htmlContent;
        document.getElementById('pdf-form').submit();
    });
</script>



</html>