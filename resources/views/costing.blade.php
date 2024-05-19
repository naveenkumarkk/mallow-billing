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
    <div class="container mt-4 col-sm-6">
        <div class="card">
            <div class="card-header text-center font-weight-bold">
                Billing Page
            </div>
            <div class="card-body">
                <form name="billing-post-form" id="billing-post-form" method="post" action="{{url('generate-bill')}}" target="_blank">
                    @csrf
                    <div class="form-group col-sm-4">
                        <label for="customer-email">Customer Email</label>
                        <input type="email" id="customer-email" name="customer-email" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <h3 class="text-center" for="customer-email">Bill Section</h3>
                    </div>
                    <div id="product-fields">
                        <div class="form-row align-items-end">
                            <div class="form-group col-sm-6">
                                <label for="product-id-0">Product ID</label>
                                <select id="product-id-0" name="products[0][id]" class="form-control product-id-select" required>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="product-quantity-0">Quantity</label>
                                <input type="number" id="product-quantity-0" name="products[0][quantity]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-info" id="add-product-btn">Add New Product</button>

                    <h4>Denominations</h4>
                    @foreach ($denominations as $denomination)
                    <div class="form-group">
                        <label for="denom-{{ $denomination->value }}">{{ $denomination->value }}</label>
                        <input type="hidden" name="denominations[{{ $denomination->id }}][id]" value="{{ $denomination->id }}">
                        <input type="number" id="denom-{{ $denomination->value }}" name="denominations[{{ $denomination->id }}][value]" class="form-control">
                    </div>
                    @endforeach


                    <div class="form-group">
                        <label for="amount-paid">Amount Paid</label>
                        <input type="number" id="amount-paid" name="amount_paid" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ url('/') }}" class="btn btn-secondary">Cancel</a>
                    <a href="{{ route('customer.search') }}" class="btn btn-danger" target="_blank">Search Customer</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var productIndex = 1;

            function initializeSelect2(selector) {
                $(selector).select2({
                    ajax: {
                        url: '/search-products', // URL to fetch product data
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                product: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name, // product name to be displayed
                                        id: item.id // product ID to be used in form
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Search for a product',
                    minimumInputLength: 1,
                });
            }

            initializeSelect2('#product-id-0');

            $('#add-product-btn').click(function() {
                var newProductFields = `
                <div class="form-row align-items-end">
                    <div class="form-group col-sm-6">
                        <label for="product-id-${productIndex}">Product ID</label>
                        <select id="product-id-${productIndex}" name="products[${productIndex}][id]" class="form-control product-id-select" required>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="product-quantity-${productIndex}">Quantity</label>
                        <input type="number" id="product-quantity-${productIndex}" name="products[${productIndex}][quantity]" class="form-control" required>
                    </div>
                </div>
                `;

                $('#product-fields').append(newProductFields);
                initializeSelect2(`#product-id-${productIndex}`);
                productIndex++;
            });
        });
    </script>
</body>

</html>