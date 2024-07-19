<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #b8c6db;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #3a4a7b;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .code-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .code-input {
            width: 50px;
            height: 50px;
            border: none;
            border-radius: 25px;
            background-color: #e8eef9;
            font-size: 24px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }
        button {
            padding: 10px 30px;
            background-color: white;
            color: #3a4a7b;
            border: 2px solid #3a4a7b;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
        }
        button:hover {
            background-color: #3a4a7b;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enter Verification Code</h1>
        <p>Make sure the verification code is correct.</p>
        <div class="code-inputs">
            <input type="text" class="code-input" maxlength="1">
            <input type="text" class="code-input" maxlength="1">
            <input type="text" class="code-input" maxlength="1">
            <input type="text" class="code-input" maxlength="1">
            <input type="text" class="code-input" maxlength="1">
            <input type="text" class="code-input" maxlength="1">
        </div>
        <button>CONFIRM</button>
    </div>
</body>
</html>