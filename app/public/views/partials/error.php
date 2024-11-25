<?php
$error_message = $_SESSION['error_message'];
unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #292929;
            color: #FFFFFF;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .error-container {
            text-align: center;
            padding: 20px;
        }

        .error-container h1 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        .error-container p {
            margin: 0;
            font-size: 18px;
        }

        .image {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
<section class="error-container">
    <img class="image" src="../../assets/images/wrench-svgrepo-com.svg" alt="Wrench">
    <h1>Error</h1>
    <p><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>We are trying to fix it. Please visit the website later.</p>
</section>
</body>
</html>
