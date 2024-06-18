<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
</head>
<body>
    <h1>Report</h1>
    <table>
        <thead>
            <tr>
                <th>Created At</th>
                <th>Transaction ID</th>
                <th>Transaction Code</th>
                <th>User ID</th>
                <!-- Add more table headers for other properties as needed -->
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction['createdAt'] }}</td>
                <td>{{ $transaction['transactionId'] }}</td>
                <td>{{ $transaction['transactionCode'] }}</td>
                <td>{{ $transaction['userId'] }}</td>
                <!-- Add more table cells for other properties as needed -->
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
