<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <style>
    body {
      min-height: 100vh;
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      margin: 0;
      background-color: #7cbeea00;
    }

    .history-container {
      width: 100%;
      padding: 20px;
      box-sizing: border-box;
    }

    .history-title {
      font-size: 30px;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      color: #5a83de;
      padding-bottom: 50px;
      text-align: left;
      font-weight: bold; 
    }

    .history-item {
      background-color: #e7e4f8;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .history-item h3 {
      margin: 0;
      font-size: 25px;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      color: #4299e1;
    }

    .history-item p {
      margin: 5px 0;
      color: #4a4a4a;
      font-size: 14px;
    }

    .right-container {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      justify-content: space-between;
      margin-top: 10px;
    }

    .stars {
      color: #ffc107;
      font-size: 30px;
    }

    .view-details {
      color: #3b5998;
      text-decoration: none;
      font-size: 14px;
      margin-top: 10px;
    }

    .view-details:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="history-container">
    <span class="history-title">History</span>
    <div class="history-item">
        <div>
            <h3>Delivery Helper</h3>
            <p>Joseph Carlo Bersoto</p>
            <p>June 1, 2024</p>
        </div>
        <div class="right-container">
            <a href="view-details-history.html" class="view-details">View Details</a>
        </div>
    </div>
    <div class="history-item">
        <div>
            <h3>Driver</h3>
            <p>Joseph Carlo Bersoto</p>
            <p>June 1, 2024</p>
        </div>
        <div class="right-container">
            <a href="view-details-history.html" class="view-details">View Details</a>
        </div>
    </div>
    <div class="history-item">
        <div>
            <h3>Family Chef</h3>
            <p>Joseph Carlo Bersoto</p>
            <p>June 1, 2024</p>
        </div>
        <div class="right-container">
            <a href="view-details-history.html" class="view-details">View Details</a>
        </div>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
