<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Log Reward List</h2>
                </div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Content</th>
                    <th>Order ID</th>
                    <th>Custommer</th>
                    <th>Reward level 1</th>
                    <th>Reward level 2</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logRewards as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{!! $log->content !!}</td>
                        <td>{{ $log->order_id }}</td>
                        <td>{{ isset($log->custommer) ? $log->custommer->name : "" }}</td>
                        <td>{{ isset($log->reward_lv1) ? $log->reward_lv1->name : "" }}</td>
                        <td>{{ isset($log->reward_lv2) ? $log->reward_lv2->name : "" }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
        {!! $logRewards->links() !!}
    </div>
</body>
</html>