<?php 


require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;


$id = $_SESSION['user_id'];

// $sql = "SELECT job_requests.id as jr_id, job_requests.user_id as jr_uid, job_requests.job_id as jr_jobid, job_requests.employer_id as jr_empid, job_requests.type as jr_type,
//         job_requests.status as jr_stat, job_requests.is_accepted as jr_accept, users.id as user_id, users.firstname, users.lastname, job_listings.id as job_list_id, job_listings.job as job_list_job,
//          job_listings.date as job_list_date, job_listings.time as job_list_time, job_listings.salary_offer as job_list_sal, job_listings.location as job_list_loc, job_listings.responsibilities as job_list_respo, 
//          job_listings.qualifications as job_list_quali, job_listings.accepted as job_list_accept, job_offers.job as jo_job, job_offers.date as jo_date, job_offers.time as jo_time FROM job_requests
//         LEFT JOIN users ON job_requests.employer_id = users.id
//         LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
//         LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
//         WHERE job_requests.user_id = ? 
//         AND job_requests.is_accepted = 1";

$sql = "SELECT job_requests.*, job_requests.id as job_req_id, job_requests.type as job_req_type, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, 
  job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali,
   users.id as userid ,users.firstname as user_fname, users.lastname as user_lname, users.email as user_email, users.home_address as user_address FROM job_requests
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
        INNER JOIN users ON job_requests.user_id = users.id
        AND job_requests.status = 1
        AND job_requests.user_id = ?
        AND job_requests.is_accepted = 1
        ORDER BY job_requests.created_at DESC";

  // echo $job_type . " " . $location . ' query: ' . $sql; die();
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    
    $stmt->close();
  }
  
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <!-- <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'> -->
  <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    html, body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
    }

    #calendar{
      margin: 10px;
    }
    

  
  </style>
</head>
<body>
<?php 
      $data = [];
      $events = [];
      if ($result != null) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        foreach($data as $row){
          $time = $row['job_offer_time'] ?? $row['time'];
          $title = $row['job_offer_job'] ?? $row['job'];
          $event = [
            "title" => 'as ' . $title,
            "start" => $row['job_offer_date'] == null ? $row['date'] : $row['job_offer_date'] . " " . $time
          ];

          array_push($events, $event);
        }

        // echo json_encode($events);
        // die();
      } else 
        echo '';
    ?>
  <input type="hidden" name="events" value="<?php echo json_encode($events);?>" id="events" >
  <div class="container p-5" style="padding: 20px;">
    <div id="calendar" class="m-5"></div>
  </div>
  <!-- Scripts -->
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
  <script>
    

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var events = document.getElementById('events');
    

    console.log();

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      initialDate: '2024-07-07',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      events: <?php echo json_encode($events);?>
      
      
    });

    calendar.render();
  });

  </script>
</body>
</html>
