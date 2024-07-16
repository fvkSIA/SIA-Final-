
<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

$id = $_SESSION['user_id'];

$sql = "SELECT job_requests.id as job_req_id, job_requests.user_id as job_req_userid, job_requests.employer_id as job_req_empid, job_requests.type as job_req_type, job_requests.status as job_req_stat, job_requests.is_accepted as job_req_acc, job_listings.id as job_list_id, job_listings.job as job_list_job, job_listings.date as job_list_data, job_listings.time as job_list_time, job_listings.time as job_list_type, job_listings.salary_offer as job_list_sal, job_listings.location as job_list_loc, job_listings.responsibilities as job_list_respo, job_listings.qualifications as job_list_quali, job_listings.accepted as job_list_accept, job_offers.* FROM job_requests
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id 
        WHERE job_requests.status = 0 
        AND job_requests.is_accepted = 1
        AND job_requests.employer_id = ?";

if ($stmt = $conn->prepare($sql)){
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
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
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
            display: flex;
            font-family: 'Poppins', sans-serif;
            flex-direction: column; /* Changed to column to stack elements vertically */
            align-items: center;
            background-color: #7cbeea00;
}


        .fold-lines {
            position: absolute;
            left: -20px;
            height: 100%;
            width: 20px;
            background-color: #f1f1f1;
            cursor: pointer;
            z-index: 10;
        }
        .fold-lines:before, .fold-lines:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            height: 2px;
            width: 100%;
            background-color: #333;
            transform-origin: center;
        }
        .fold-lines:before {
            transform: translate(-50%, -50%) rotate(45deg);
        }
        .fold-lines:after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }
        main {
            width: 98%;
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

<main><br>

    <span class="text-blue-500" style="font-size: 25px; font-weight: bold;">JOB ORDER</span>
    
    <?php if($data):?>
        <?php foreach($data as $row):?>
            <div class="bg-purple-100 p-8 rounded-lg mb-4">
                <div class="bg-#f1f1f1 p-4 mb-1">
                    <div style="display: flex; justify-content: space-between; max-width: 100%; ">
                        <div style="width: 20%; margin: 1px; box-sizing: border-box;">
                            <p style="font-weight: bold; font-size: 25px;">
                                <?php echo $row['job'];?>
                            </p><p style="margin-top: 15px; font-weight: bold;">
                                <?php echo $row['location'];?>
                            </p><p style="margin-top: 15px; font-weight: bold;">
                                <?php if($row['job_req_stat'] == 0) :?>
                                    <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-full text-lg" style="height: 28px; padding-top: 1px;">
                                    ONGOING
                                </button>
                                <?php endif;?>
                            </p>
                        </div>
                        <!-- <div style="width: 45%; margin: 1px; box-sizing: border-box;">
                            <p style="font-size: 25px;">
                                <i style="color: red;"> <--- backend echo</i>
                            </p><p style="margin-top: 15px;">
                                <i style="color: red;"> <--- backend echo</i>
                            </p><p style="margin-top: 15px;">
                                <i style="color: red;"> <--- backend echo</i>
                            </p>
                        </div> -->
                        <div style="width: 10%; margin-left: 20px; box-sizing: border-box;">
                            <a href="employerongoingdetails.php?id=<?php echo $row['job_req_id'];?>" style="text-decoration: underline; display: flex; justify-content: center; margin-top: 100px;">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    <?php else:?>
        <div class="container mx-auto mt-8 p-4 border-t-4 border-indigo-200 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between">
          <p>NO ONGOING JOB</p>
        </div>
      </div>
    <?php endif;?>
    
</main>

<script>
    function foldAside() {
        const aside = document.querySelector('aside');
        aside.classList.toggle('folded');
    }
</script>






<script src="script.js"></script>
</body>
</html>
