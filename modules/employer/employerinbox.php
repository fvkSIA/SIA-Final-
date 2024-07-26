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
    <title>Employer Dashboard</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
        :root {
            --color-default: #004f83;
            --color-second: #0067ac;
            --color-white: #fff;
            --color-body: #e4e9f7;
            --color-light: #e0e0e0;
        }
        * {
            padding: 0%;
            margin: 0%;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
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
        .delete-button {
            color: red;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 24px;
        } 
    </style>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this item?");
        }
    </script>
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
    <?php if($data): ?>
        <?php foreach($data as $row): ?>
            <?php if($row['jr_type'] === 1): ?>
                <?php if($row['is_accepted'] == 1): ?>
                    <tr>
                        <td colspan="3">
                            <div class="details">
                                <b><?php echo $row['job_seek_fname'] . ' ' . $row['job_seek_lname'];?> | Accepted the job offer you have been requested! </b>
                                <a href="employeracceptedoffermessage.php?fname=<?php echo $row['job_seek_fname'];?>&lname=<?php echo $row['job_seek_lname'];?>">View Details</a>
                                <form action="delete_request.php" method="post" onsubmit="return confirmDelete();" style="display:inline;">
                                    <input type="hidden" name="jr_id" value="<?php echo $row['jr_id']; ?>">
                                    <button type="submit" class="delete-button">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

            <?php elseif($row['jr_type'] === 2): ?>
                <?php if ($row['is_accepted'] == 0): ?>
                    <tr>
                        <td colspan="3">
                            <div class="details">
                                <b><?php echo $row['job_seek_fname'] . ' ' . $row['job_seek_lname'];?> | Applied for the job: <?php echo $row['job_list_job']; ?></b>
                                <a href="jobseekerapply.php?id=<?php echo $row['jr_uid'];?>&jrid=<?php echo $row['jr_id'];?>">View Details</a>
                                <form action="delete_request.php" method="post" onsubmit="return confirmDelete();" style="display:inline;">
                                    <input type="hidden" name="jr_id" value="<?php echo $row['jr_id']; ?>">
                                    <button type="submit" class="delete-button">
                                        <i class="bx bx-trash"></i> 
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

            <?php elseif($row['jr_type'] === 3): ?>
            <?php else: ?>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php else: ?>
        <tr>
        <td colspan="3">
            <div class="details">
                <b>No items yet</b>
            </div>
        </td>
    </tr>
    <?php endif; ?>
</table>

<?php include '../employer/em_footer.html'; ?>

</body>
</html>
