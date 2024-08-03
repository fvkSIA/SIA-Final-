<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

$id = $_SESSION['user_id'];

// Ensure each job seeker can only apply once per job
$sql = "SELECT job_requests.id as jr_id, job_requests.user_id as jr_uid, job_requests.job_id as jr_jobid, job_requests.employer_id as jr_empid, job_requests.type as jr_type,
           job_requests.status as jr_comp, job_requests.is_accepted, users.id as user_id, users.firstname, users.lastname, job_listings.id as job_list_id, job_listings.job as job_list_job,
           job_listings.date as job_list_date, job_listings.time as job_list_time, job_listings.salary_offer as job_list_sal, job_listings.location as job_list_loc, job_listings.responsibilities as job_list_respo, 
           job_listings.qualifications as job_list_quali, job_listings.accepted as job_list_accept,
           job_offers.*, a.firstname as job_seek_fname, a.lastname as job_seek_lname, a.middlename as job_seek_mname, a.email as job_seek_email, a.phone_number as job_seek_phone 
           FROM job_requests
           LEFT JOIN users ON job_requests.employer_id = users.id
           LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
           LEFT JOIN users as a ON job_requests.user_id = a.id
           LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
           WHERE job_requests.employer_id = ?
           AND job_requests.id IN (
               SELECT MAX(job_requests.id) 
               FROM job_requests 
               GROUP BY job_requests.job_id, job_requests.user_id
           )
           ORDER BY job_requests.id DESC, job_listings.date DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
}
$num_rows = ($result != null) ? $result->num_rows : 0;

// Store the count in a session variable
$_SESSION['inbox_notification_count'] = $num_rows;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <style>
        .notification-item {
            transition: all 0.3s ease;
        }
        .notification-item.active {
            background-color: #F6E5E4;
            border-left: 4px solid #4C100C;
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
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            padding: 8px 8px 8px 50px;
            height: 40px;
        }
        .details {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .details b {
            flex: 1;
        }
        .delete-button {
            margin-left: 10px;
            color: red;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 24px;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-5">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-1/3 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Notifications</h2>
                <div class="space-y-4">
                    <?php 
                    $data = [];
                    if ($result != null) {
                        $data = $result->fetch_all(MYSQLI_ASSOC);
                    }
                    if($data):
                        foreach($data as $row): 
                            if(($row['jr_type'] === 1 && $row['is_accepted'] == 1) || 
                               ($row['jr_type'] === 2 && $row['is_accepted'] == 0)): 
                    ?>
                    <div id="notification-<?php echo $row['jr_id']; ?>" class="notification-item bg-gray-50 p-4 rounded-md">
                        <p class="text-sm font-medium text-gray-600 mb-2">
                            <?php 
                            if($row['jr_type'] === 1) {
                                echo $row['job_seek_fname'] . ' ' . $row['job_seek_lname'] . ' accepted the job offer you requested!';
                            } elseif($row['jr_type'] === 2) {
                                echo $row['job_seek_fname'] . ' ' . $row['job_seek_lname'] . ' applied for the job: ' . $row['job_list_job'];
                            }
                            ?>
                        </p>
                        <div class="flex justify-between items-center">
                            <button onclick="loadContent('<?php echo $row['jr_type'] === 1 ? 'employeracceptedoffermessage.php?fname=' . $row['job_seek_fname'] . '&lname=' . $row['job_seek_lname'] : 'jobseekerapply.php?id=' . $row['jr_uid'] . '&jrid=' . $row['jr_id'];?>', <?php echo $row['jr_id']; ?>)" class="text-blue-600 hover:text-maroon-800 text-sm font-medium">
                                View Details
                            </button>
                            <form action="delete_request.php" method="post" onsubmit="return confirmDelete();" class="inline">
                                <input type="hidden" name="jr_id" value="<?php echo $row['jr_id']; ?>">
                                <button type="submit" class="delete-button">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php 
                            endif;
                        endforeach;
                    else:
                    ?>
                    <p class="text-gray-500">No notifications yet</p>
                    <?php endif; ?>
                </div>
            </div>
            <div id="rightContent" class="w-full md:w-2/3 bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-500">Select a notification to view details</p>
            </div>
        </div>
    </div>
    <?php include '../employer/em_footer.html'; ?>

    <script>
        let currentJrId = null;

        function confirmDelete() {
            return confirm("Are you sure you want to delete this item?");
        }

        function markAsRead(jr_id) {
            fetch('mark_as_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'jr_id=' + jr_id
            }).then(response => {
                if (response.ok) {
                    console.log('Message marked as read.');
                } else {
                    console.log('Failed to mark as read.');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }

        function loadContent(url, jr_id) {
            if (currentJrId !== jr_id) {
                if (currentJrId) {
                    document.getElementById('notification-' + currentJrId).classList.remove('active');
                }
                
                document.getElementById('notification-' + jr_id).classList.add('active');

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('rightContent').innerHTML = data;
                        markAsRead(jr_id);
                        currentJrId = jr_id;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                document.getElementById('rightContent').innerHTML = "<p class='text-gray-500'>Select a notification to view details</p>";
                currentJrId = null;
            }
        }
    </script>
</body>
</html>
