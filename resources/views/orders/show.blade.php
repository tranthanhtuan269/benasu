<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Order Detail</h2>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-4">
                User Name
            </div>
            <div class="col-lg-8">
                {{ $order_detail[0]->name }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-4">
                User Email
            </div>
            <div class="col-lg-8">
                {{ $order_detail[0]->email }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-4">
                User Address
            </div>
            <div class="col-lg-8">
                {{ $order_detail[0]->address1 .'-'. $order_detail[0]->city .'-'. $order_detail[0]->state .'-'. $order_detail[0]->countryid }}
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Prod Id</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Rebate</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order_detail as $prod)
                    <tr>
                        <td>{{ $prod->order_id }}</td>
                        <td><img src="{{ $prod->product_mediaurl }}" width="100"></td>
                        <td>{{ $prod->product_name }}</td>
                        <td>{{ $prod->product_quantity }}</td>
                        <td>{{ $prod->product_currencyid . ' ' . $prod->product_price }}</td>
                        <td>{{ $prod->product_currencyid . ' ' . $prod->product_rebate }}</td>
                        <td>{{ $prod->product_currencyid . ' ' . number_format($prod->product_price * $prod->product_quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>