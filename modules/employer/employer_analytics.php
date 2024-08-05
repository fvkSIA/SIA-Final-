<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

$id = $_SESSION['user_id'];

$completedJobTypesQuery = "SELECT 
    COALESCE(job_listings.job, job_offers.job) as job_type, 
    COUNT(*) as count 
FROM job_requests
LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
WHERE job_requests.status = 1
AND job_requests.employer_id = ?
GROUP BY job_type 
ORDER BY count DESC";

$stmt = mysqli_prepare($conn, $completedJobTypesQuery);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$completedJobTypesResult = mysqli_stmt_get_result($stmt);

$completedJobTypes = [];
while ($row = mysqli_fetch_assoc($completedJobTypesResult)) {
    $completedJobTypes[] = $row;
}

// Convert PHP array to JSON for use in JavaScript
$completedJobTypesJson = json_encode($completedJobTypes);

$id = $_SESSION['user_id'];

$sql = "SELECT job_requests.id as job_req_id, job_requests.user_id as job_req_userid, job_requests.employer_id as job_req_empid, job_requests.type as job_req_type, job_requests.status as job_req_stat, job_requests.is_accepted as job_req_acc, job_listings.id as job_list_id, job_listings.job as job_list_job, job_listings.date as job_list_data, job_listings.time as job_list_time, job_listings.time as job_list_type, job_listings.salary_offer as job_list_sal, job_listings.location as job_list_loc, job_offers.location as job_list_loca, job_listings.responsibilities as job_list_respo, job_listings.qualifications as job_list_quali, job_listings.accepted as job_list_accept, job_offers.* FROM job_requests
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id 
        WHERE job_requests.status = 0 
        AND job_requests.is_accepted = 1
        AND job_requests.employer_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$ongoingJobs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ongoingJobs[] = $row;
}

$ongoingJobsCount = count($ongoingJobs);

$userid = $_SESSION['user_id'];

$sql_completed = "SELECT job_requests.*, job_requests.id as job_req_id, job_requests.type as job_req_type, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, 
    job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali,
     users.id as userid, users.firstname as user_fname, users.lastname as user_lname, users.email as user_email, users.home_address as user_address FROM job_requests
          LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
          LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
          INNER JOIN users ON job_requests.user_id = users.id
          WHERE job_requests.status = 1
          AND job_requests.employer_id = ? 
          ORDER BY job_requests.created_at DESC";

$stmt_completed = mysqli_prepare($conn, $sql_completed);
mysqli_stmt_bind_param($stmt_completed, "i", $userid);
mysqli_stmt_execute($stmt_completed);
$result_completed = mysqli_stmt_get_result($stmt_completed);

$completedJobs = [];
while ($row = mysqli_fetch_assoc($result_completed)) {
    $completedJobs[] = $row;
}

$completedJobsCount = count($completedJobs);

$employer_id = $_SESSION['user_id'];


$sql_job_posts = "SELECT COUNT(*) as job_post_count FROM job_listings WHERE employer_id = ?";
$stmt_job_posts = mysqli_prepare($conn, $sql_job_posts);
mysqli_stmt_bind_param($stmt_job_posts, "i", $employer_id);
mysqli_stmt_execute($stmt_job_posts);
$result_job_posts = mysqli_stmt_get_result($stmt_job_posts);
$job_posts_count = mysqli_fetch_assoc($result_job_posts)['job_post_count'];

$topJobsQuery = "SELECT 
    COALESCE(jl.job, jo.job) as type_of_jobs,
    COUNT(DISTINCT jl.id) as no_of_jobs,
    SUM(CASE WHEN jr.is_accepted = 1 AND jr.status = 0 THEN 1 ELSE 0 END) as no_of_on_going,
    SUM(CASE WHEN jr.status = 1 THEN 1 ELSE 0 END) as no_of_hired_workers
FROM (
    SELECT DISTINCT job FROM job_listings WHERE employer_id = ?
    UNION
    SELECT DISTINCT job FROM job_offers WHERE employer_id = ?
) AS unique_jobs
LEFT JOIN job_listings jl ON unique_jobs.job = jl.job AND jl.employer_id = ?
LEFT JOIN job_offers jo ON unique_jobs.job = jo.job AND jo.employer_id = ?
LEFT JOIN job_requests jr ON (jr.job_id = jl.id OR jr.job_id = jo.id) AND jr.employer_id = ?
GROUP BY type_of_jobs
ORDER BY no_of_jobs DESC
LIMIT 10";

$stmt = mysqli_prepare($conn, $topJobsQuery);
mysqli_stmt_bind_param($stmt, "iiiii", $id, $id, $id, $id, $id);  // Assuming $id is the employer_id
mysqli_stmt_execute($stmt);
$topJobsResult = mysqli_stmt_get_result($stmt);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employer Dashboard - Performance Summary</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="icon" type="image/png" href="../HanapKITA.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
    }
    .container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 40px 20px;
    }
    .ongoing-title {
      font-size: 32px;
      color: #6b0d0d; 
      margin-bottom: 30px;
      border-bottom: 3px solid #6b0d0d;
      padding-bottom: 10px;
      text-align: left;
    }
    .job-card {
      transition: all 0.3s ease;
    }
    .job-card:hover {
      transform: translateY(-5px);
    }
    .performance-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 20px;
    }
    .performance-card {
      background-color: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    .performance-number {
      font-size: 48px;
      font-weight: bold;
      color: #6b0d0d;
    }
    .performance-label {
      font-size: 18px;
      color: #333;
      margin-top: 10px;
    }
    .performance-date {
      font-size: 14px;
      color: #666;
      margin-top: 5px;
    }
    .performance-change {
      font-size: 14px;
      color: #22c55e;
      margin-top: 5px;
    }
    .controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .dropdown {
      padding: 8px 12px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }
    .print-btn {
      padding: 8px 12px;
      background-color: #6b0d0d;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .chart-container {
      background-color: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-top: 30px;
    }
    table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .print-button {
            float: right;
            margin-bottom: 10px;
        }
  </style>
</head>
<body class="bg-gray-100">
  <div class="container">
    <h1 class="ongoing-title">Performance Summary</h1>
    <div class="controls">
    <select id="timeframe-select" class="dropdown" onchange="updateTimeframe()">
      <option value="monthly">Monthly</option>
      <option value="weekly">Weekly</option>
      <option value="yearly">Yearly</option>
    </select>
      <button onclick="window.print()" class="print-button print-btn">
        <i class="fas fa-print"></i> Print Report
      </button>
    </div>
    <div class="performance-grid">
        <div class="performance-card">
            <div class="performance-number"><?php echo $ongoingJobsCount; ?></div>
            <div class="performance-label">On Going Jobs</div>
            <div class="performance-date">Aug 2024</div>
            <div class="performance-change">
                <!-- You may want to calculate this dynamically -->
                3 <span class="text-green-500">(0.03% ▲)</span>
                <div class="performance-date">July 2024</div>
            </div>
        </div>
        <div class="performance-card">
            <div class="performance-number"><?php echo $completedJobsCount; ?></div>
            <div class="performance-label">Completed Jobs</div>
            <div class="performance-date">Aug 2024</div>
            <div class="performance-change">
                <!-- You may want to calculate this dynamically -->
                3 <span class="text-green-500">(0.03% ▲)</span>
                <div class="performance-date">July 2024</div>
            </div>
        </div>
      <div class="performance-card">
      <div class="performance-number"><?php echo $job_posts_count; ?></div>
        <div class="performance-label">Job Posted</div>
        <div class="performance-date">Aug 2024</div>
        <div class="performance-change">
          3 <span class="text-green-500">(0.03% ▲)</span>
          <div class="performance-date">July 2024</div>
        </div>
      </div>
    </div>
    
    <h2 class="ongoing-title" style="margin-top: 40px;">Top Jobs</h2>
<div class="chart-container">
  <table>
    <tr>
      <th>Type of Jobs</th>
      <th>No. of Jobs</th>
      <th>No. of On Going</th>
      <th>No. of Hired Workers</th>
    </tr>
    <?php
    if (mysqli_num_rows($topJobsResult) > 0) {
      while ($row = mysqli_fetch_assoc($topJobsResult)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["type_of_jobs"]) . "</td>";
        echo "<td>" . $row["no_of_jobs"] . "</td>";
        echo "<td>" . $row["no_of_on_going"] . "</td>";
        echo "<td>" . $row["no_of_hired_workers"] . "</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='4'>No jobs found</td></tr>";
    }
    ?>
  </table>
</div>


    <!-- New section for the Most Employed Workers graph -->
    <h2 class="ongoing-title" style="margin-top: 40px;">Completed Jobs Distribution</h2>
  <div class="chart-container">
    <canvas id="completedJobTypesChart"></canvas>
  </div>

  <script>
    let chart;
    let originalChartData;

    document.addEventListener('DOMContentLoaded', function() {
      // Use the PHP-generated JSON data for completed job types
      const completedJobTypesData = <?php echo $completedJobTypesJson; ?>;

      const ctx = document.getElementById('completedJobTypesChart').getContext('2d');
      chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: completedJobTypesData.map(job => job.job_type),
          datasets: [{
            label: 'Number of Completed Jobs',
            data: completedJobTypesData.map(job => job.count),
            backgroundColor: '#6b0d0d',
            borderColor: '#6b0d0d',
            borderWidth: 1
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          scales: {
            x: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          },
          plugins: {
            legend: {
              display: false
            },
            title: {
              display: true,
              text: 'Completed Jobs Distribution'
            }
          }
        }
      });

      // Store the original chart data
      originalChartData = {
        labels: [...chart.data.labels],
        data: [...chart.data.datasets[0].data]
      };
    });

    let originalData = [];

    const dummyData = {
      weekly: [
        { number: 2, date: 'Week of Aug 1-7, 2024', change: '1 <span class="text-green-500">(50% ▲)</span>', prevDate: 'Week of Jul 25-31, 2024' },
        { number: 3, date: 'Week of Aug 1-7, 2024', change: '2 <span class="text-green-500">(66% ▲)</span>', prevDate: 'Week of Jul 25-31, 2024' },
        { number: 4, date: 'Week of Aug 1-7, 2024', change: '2 <span class="text-green-500">(50% ▲)</span>', prevDate: 'Week of Jul 25-31, 2024' }
      ],
      yearly: [
        { number: 60, date: 'Year 2024', change: '54 <span class="text-green-500">(10% ▲)</span>', prevDate: 'Year 2023' },
        { number: 340, date: 'Year 2024', change: '108 <span class="text-green-500">(10% ▲)</span>', prevDate: 'Year 2023' },
        { number: 400, date: 'Year 2024', change: '162 <span class="text-green-500">(10% ▲)</span>', prevDate: 'Year 2023' }
      ]
    };

    const dummyChartData = {
      weekly: {
        labels: ['Welder', 'Electrician'],
        data: [2, 1]
      },
      yearly: {
        labels: ['Carpenter', 'Driver', 'Electrician', 'Plumber', 'Security guard', 'Welder', 'Housekeeper'],
        data: [60, 75, 45, 55, 40, 30, 35]
      }
    };

    function updateTimeframe() {
      const timeframe = document.getElementById('timeframe-select').value;
      const performanceCards = document.querySelectorAll('.performance-card');
      
      if (timeframe === 'monthly') {
        // Restore original data for performance cards
        performanceCards.forEach((card, index) => {
          const numberElement = card.querySelector('.performance-number');
          const dateElement = card.querySelector('.performance-date');
          const changeElement = card.querySelector('.performance-change');
          
          numberElement.textContent = originalData[index].number;
          dateElement.textContent = originalData[index].date;
          changeElement.innerHTML = originalData[index].change;
        });

        // Restore original chart data
        chart.data.labels = originalChartData.labels;
        chart.data.datasets[0].data = originalChartData.data;
        chart.options.plugins.title.text = 'Completed Jobs Distribution';
      } else {
        // Update performance cards with dummy data
        performanceCards.forEach((card, index) => {
          const numberElement = card.querySelector('.performance-number');
          const dateElement = card.querySelector('.performance-date');
          const changeElement = card.querySelector('.performance-change');
          
          const data = dummyData[timeframe][index];
          
          numberElement.textContent = data.number;
          dateElement.textContent = data.date;
          changeElement.innerHTML = data.change;
          changeElement.innerHTML += `<div class="performance-date">${data.prevDate}</div>`;
        });

        // Update chart with hardcoded dummy data
        chart.data.labels = dummyChartData[timeframe].labels;
        chart.data.datasets[0].data = dummyChartData[timeframe].data;
        chart.options.plugins.title.text = `Completed Jobs Distribution (${timeframe})`;
      }

      // Update the chart
      chart.update();
    }

    // Store original data on page load
    document.addEventListener('DOMContentLoaded', () => {
      const performanceCards = document.querySelectorAll('.performance-card');
      performanceCards.forEach(card => {
        originalData.push({
          number: card.querySelector('.performance-number').textContent,
          date: card.querySelector('.performance-date').textContent,
          change: card.querySelector('.performance-change').innerHTML
        });
      });
    });
  </script>
</body>
</html>