<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello, <?php echo $staffRow['name'];?>!</h1>
        <p>We have generated your payslip for the month of <?php echo $result['month'];?> <?php echo $result['year'];?>. Please click the link below to view it:</p>
        <a href="<?php echo base_url('welcome/view_payroll/'.$result['hash_id']);?>" class="button">View Payroll Receipt</a>
        <p>If you have any questions or need assistance, feel free to reply to this email.</p>
        <p>Best regards,<br><?php echo $setting->name;?></p>
    </div>
</body>
</html>
