<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;


$id = $_SESSION['user_id'];

// $sql = "SELECT job_requests.id as jr_id, job_requests.user_id as jr_uid, job_requests.job_id as jr_jobid, job_requests.employer_id as jr_empid, job_requests.type as jr_type,
//         job_requests.status as jr_stat, job_requests.is_accepted as jr_accept, users.id as user_id, users.firstname, users.lastname, job_listings.*, job_offers.* FROM job_requests
//         INNER JOIN users ON job_requests.user_id = users.id 
//         INNER JOIN job_listings ON job_requests.job_id = job_listings.id
//         INNER JOIN job_offers ON job_requests.job_id = job_offers.id
//         WHERE job_listings.employer_id = ?";

$sql = "SELECT job_requests.id as jr_id, job_requests.user_id as jr_uid, job_requests.job_id as jr_jobid, job_requests.employer_id as jr_empid, job_requests.type as jr_type,
        job_requests.status as jr_comp, job_requests.is_accepted, users.id as user_id, users.firstname, users.lastname, job_listings.*, job_offers.*, a.firstname as job_seek_fname, a.lastname as job_seek_lname, a.middlename as job_seek_mname, a.email as job_seek_email, a.phone_number as job_seek_phone FROM job_requests
        LEFT JOIN users ON job_requests.employer_id = users.id
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN users as a ON job_requests.user_id = a.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
        WHERE job_requests.employer_id = ?";

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
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

:root{
  --color-default:#004f83;
  --color-second:#0067ac;
  --color-white:#fff;
  --color-body:#e4e9f7;
  --color-light:#e0e0e0;
}

*{
  padding: 0%;
  margin: 0%;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body{
  min-height: 100vh;
  font-family: Arial, sans-serif;
  display: flex;
  flex-direction: column; /* Changed to column to stack elements vertically */
  align-items: center;
  height: 100vh;
  margin: 0;
  background-color: #7cbeea00;
}



  
table {
  border-collapse: collapse;
  width: 100%;
  margin-top: 20px;
  margin-left: auto;
  margin-right: auto;
}


        td {
            border: 1px solid #ddd;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-size: 18px;
            padding: 8px 8px 8px 50px; /* Adjusted padding to move text to the right */
            height: 40px;
        }

        .details {
            text-align: right;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-size: 15px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .details span {
            margin-left: auto;
            text-decoration: underline;
            cursor: pointer;
        }
        .footer {
            width: 100%;
            background-color: #f8f9fa00;
            padding: 20px;
            font-family: Arial, sans-serif;
            margin-top: 30px; /* Adjusted margin-top for space before footer */
            border-radius: 5px;
            box-sizing: border-box; /* Ensure padding and border are included in width */
        }
        .footer-section {
            display: flex;
            justify-content: space-around;
            width: 100%;
            max-width: 1200px; /* Added max-width for content control */
            margin: 0 auto;
        }
        .footer-column {
            list-style: none;
            padding: 0;
        }
        .footer-column li {
            margin-bottom: 10px;
        }
        .footer-column li a {
            text-decoration: none;
            color: #000;
        }
        .footer-column h4 {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 0.9em;
            color: #6c757d;
        }
        .footer-bottom a {
            text-decoration: none;
            color: inherit;
        }
        .footer-bottom a:hover {
            text-decoration: underline;
        }
  </style>
</head>
<body>

<?php 
      $data = [];
      if ($result != null)
        $data = $result->fetch_all(MYSQLI_ASSOC);
      else 
        echo '';
    ?>

  <table>
    <?php if($data):?>
        <?php foreach($data as $row): ?>
            <!-- <?php echo $row['jr_type'];?> -->
            <?php if($row['jr_type'] === 1): ?>
                <?php if($row['is_accepted'] == 1): ?>
                    <tr>
                        <td colspan="3">
                            <div class="details">
                                <b><?php echo $row['job_seek_fname'] . ' ' . $row['job_seek_lname'];?> | Accepted the job offer you have been requested! </b>
                                <a href="#">View Details</a>
                            </div>
                        </td>
                    </tr>
                <?php endif;?>
                
            <?php elseif($row['jr_type'] === 2): ?>
                <tr>
                    <td colspan="3">
                        <div class="details">
                            <b><?php echo $row['job_seek_fname'] . ' ' . $row['job_seek_lname'];?> | Want's to apply to your job post </b>
                            <a href="jobseekerapply.php?id=<?php echo $row['jr_uid'];?>">View Details</a>
                        </div>
                    </td>
                </tr>
            <?php elseif($row['jr_type'] === 3):?>
            <?php else:?>
            <?php endif;?>
            
        <?php endforeach;?>
    <?php else:?>
        <tr>
        <td colspan="3">
            <div class="details">
                <b>No items yet</b>
                <!-- <a href="employeracceptedoffermessage.html">View Details</a> -->
            </div>
        </td>
    </tr>
    <?php endif;?>
    
    <!-- Additional rows removed for brevity -->
</table>
<div style="height: 50px; width: 100%;"></div> <!-- Spacer div -->
<footer class="footer">
    <div class="footer-section">
        <ul class="footer-column">
            <h4>Job Seekers</h4>
            <li><a href="#top">Job Search</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Recommended Jobs</a></li>
            <li><a href="#">Saved Searches</a></li>
            <li><a href="#">Saved Jobs</a></li>
            <li><a href="#">Job Applications</a></li>
        </ul>
        <ul class="footer-column">
            <h4>Employers</h4>
            <li><a href="#">Registration for Free</a></li>
            <li><a href="#">Post a Job ad</a></li>
        </ul>
        <ul class="footer-column">
            <h4>About Jobstreet</h4>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Work for Jobstreet</a></li>
        </ul>
        <ul class="footer-column">
            <h4>Contact</h4>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </div>
    <div class="footer-bottom">
        <a href="#">Terms & conditions</a> | <a href="#">Security & Privacy</a>
    </div>
</footer>






</section>
<!-- Scripts -->
<script src="script.js"></script>
</body>
</html>
