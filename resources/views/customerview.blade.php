<!DOCTYPE html>
<html>
<head>
    <title>Customer Purchase History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Customer Purchase History</h2>

        <form id="searchForm">
            <div class="form-group">
                <label for="customer-email">Customer Email:</label>
                <input type="email" id="customer-email" class="form-control" name="customer-email" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div id="customerData" class="mt-4"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                var email = $('#customer-email').val();

                $.ajax({
                    url: '{{ route("customer.purchaseHistory") }}', // Adjust this route to your actual route
                    method: 'POST',
                    data: {
                        'customer-email': email,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        var customer = response.data;
                        var html = '<h3>Customer: ' + customer.email + '</h3>';
                        html += '<table class="table table-bordered">';
                        html += '<thead><tr><th>Bill Date</th><th>Total Amount</th></tr></thead>';
                        html += '<tbody>';
                       
                        customer?.purchase_info?.forEach(function(purchase) {
                            html += '<tr data-toggle="collapse" data-target="#purchase' + purchase.id + '" class="accordion-toggle">';
                            html += '<td>' + purchase?.bill_date + '</td>'; // Assuming you have created_at field
                            html += '<td>' + purchase?.paid_amount + '</td>'; // Adjust this based on your data structure
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td colspan="2" class="hiddenRow"><div class="accordion-body collapse" id="purchase' + purchase.id + '">';
                            html += '<table class="table table-bordered">';
                            html += '<thead><tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr></thead>';
                            html += '<tbody>';
                            
                            purchase.purchase_history.forEach(function(item) {
                                html += '<tr>';
                                html += '<td>' + item?.product?.name + '</td>';
                                html += '<td>' + item?.quantity + '</td>';
                                html += '<td>' + item?.selling_price_per_unit + '</td>';
                                html += '</tr>';
                            });
                            html += '</tbody>';
                            html += '</table>';
                            html += '</div></td>';
                            html += '</tr>';
                        });
                        html += '</tbody>';
                        html += '</table>';
                        $('#customerData').html(html);
                    },
                    error: function(xhr) {
                        var error = xhr.responseJSON;
                        alert(error[0]);
                    }
                });
            });
        });
    </script>
</body>
</html>
