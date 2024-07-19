<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            font-size: 30px;
            margin-top: 15px;
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 20px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        input[type="email"]::placeholder {
            color: #999;
            text-align: center;
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
        <h1>FORGOT PASSWORD</h1>
        <form>
            <input type="email" placeholder="Enter Email-Address" required>
            <br>
            <button type="submit">SUBMIT</button>
        </form>
    </div>
</body>
</html>