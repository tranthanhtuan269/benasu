<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Order List</h2>
                </div>
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
                    <th>S.No</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Price</th>
                    <th>Rebate</th>
                    <th>Tax</th>
                    <th>Payment</th>
                    <th>Delivery</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderList as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->currencyid . ' ' . $order->price }}</td>
                        <td>{{ $order->currencyid . ' ' . $order->rebate }}</td>
                        <td>{{ $order->currencyid . ' ' . $order->tax }}</td>
                        <td>{{ $order->statuspayment }}</td>
                        <td>{{ $order->statusdelivery }}</td>
                        <td>{{ $order->ctime }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('orders.show',$order->order_id) }}">Show</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $orderList->links() !!}
    </div>
</body>
</html>