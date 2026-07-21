<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Logged Out</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            max-width: 450px;
        }
        h1 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 30px;
            font-size: 16px;
        }
        a {
            display: inline-block;
            padding: 12px 25px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Session Terminated</h1>
        <p>Your session has been logged out due to system maintenance or administrative action. Please login again to regain access to your account and continue working on the platform.</p>
        <a href="<?php echo site_url('reseller'); ?>">Login Again</a>
    </div>
</body>
</html>
