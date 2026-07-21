<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Access Denied</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .denied-box {
            background: #fff;
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 15px;
            color: #c62828;
        }
        p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            padding: 10px 18px;
            background: #1976d2;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
        a:hover {
            background: #1565c0;
        }
    </style>
</head>
<body>
    <div class="denied-box">
        <h1>🚫 Access Denied</h1>
        <p>You do not have permission to access this page.</p>
        <a href="<?= site_url('reseller/dashboard'); ?>">Go to Dashboard</a>
    </div>
</body>
</html>
