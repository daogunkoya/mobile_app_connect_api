<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        main {
            padding: 20px;
        }
        section {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }
        section h2 {
            margin-top: 0;
        }
        .section-content {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            flex: 1;
            flex-direction: row;
        }
        .section-content p {
            margin: 0;
        }
    </style>
</head>
<body>
<header>
    <h1>Transaction Receipt</h1>
    <p>Created Date: {{ $createdAt }}</p>
    <p>Completed Date: {{ $createdAt }}</p>
</header>
<main>
    <section>
        <h2>Transfer Overview</h2>
        <div class="section-content">
            <div>
                <p>Amount Paid by {{ $senderFname }} {{ $senderLname }}: {{ $totalAmount }}</p>
            </div>
            <div>
                <p>Converted and sent to receiver</p>
                <p>{{ $localAmount }}</p>
            </div>
        </div>
    </section>
    <section>
        <h2>Exchange Transaction Confirmation</h2>
        <div class="section-content">
            <div>
                <p>Amount</p>
                <p>{{ $totalAmount }}</p>
                <p>Exchange Rate</p>
                <p>1.00 GBP = {{ $exchangeRate }}</p>
            </div>
            <div>
                <p>Exchange Amount</p>
                <p>{{ $localAmount }}</p>
            </div>
        </div>
    </section>
    <section>
        <h2>Sent to</h2>
        <div class="section-content">
            <div>
                <p>Name</p>
                <p>{{ $receiverFname }} {{ $receiverLname }}</p>
                <p>Account Details</p>
                <p>{{ $receiverAccountNumber }}</p>
                <p>{{ $receiverBank['name'] }}</p>
            </div>
            <div>
                <p>Reference</p>
                <p>{{ $transactionCode }}</p>
            </div>
        </div>
    </section>
</main>
</body>
</html>
