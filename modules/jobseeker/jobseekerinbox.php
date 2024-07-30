<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

$id = $_SESSION['user_id'];

// SQL query to get unique job requests or notifications for the user
$sql = "SELECT job_requests.id as jr_id, job_requests.user_id as jr_uid, job_requests.job_id as jr_jobid, job_requests.employer_id as jr_empid, job_requests.type as jr_type,
        job_requests.status as jr_comp, job_requests.is_accepted as jr_accept, users.id as user_id, users.firstname, users.lastname, job_listings.id as job_list_id, job_listings.job as job_list_job,
        job_listings.date as job_list_date, job_listings.time as job_list_time, job_listings.salary_offer as job_list_sal, job_listings.location as job_list_loc, job_listings.responsibilities as job_list_respo, 
        job_listings.qualifications as job_list_quali, job_listings.accepted as job_list_accept, job_offers.* 
        FROM job_requests
        LEFT JOIN users ON job_requests.employer_id = users.id
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
        WHERE job_requests.user_id = ?
        AND job_requests.id IN (
            SELECT MAX(job_requests.id)
            FROM job_requests
            GROUP BY job_requests.job_id
        )";


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
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    /* Your existing styles... */
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        background-color: #ffffff80;
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
    /* New styles for the View button */
    .view-button {
        background-color: #0067ac; /* Blue color */
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .view-button:hover {
        background-color: #004f83; /* Darker blue on hover */
    }
    .eye-icon::before {
        content: "\1F441"; /* Unicode for eye emoji */
        font-size: 16px;
    }
  </style>
  <script>
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
                // You can update the UI here if needed
            } else {
                console.log('Failed to mark as read.');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }
  </script>
</head>
<body>
<?php 
$data = [];
if ($result != null) {
    $data = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<table>
    <?php if($data):?>
        <?php foreach($data as $row): ?>
            <?php if($row['jr_type'] === 1): ?>
                <?php if ($row['jr_comp'] !== 1):?>
                    <tr>
                        <td colspan="3">
                            <div class="details">
                                <b><?php echo $row['firstname'] . ' ' . $row['lastname'];?> | Sent you a job offer! </b>
                                <a href="jobseekeracceptedoffer.php?id=<?php echo $row['jr_jobid'];?>&jrid=<?php echo $row['jr_id'];?>" onclick="markAsRead(<?php echo $row['jr_id']; ?>)" class="view-button"><span class="eye-icon"></span>View</a>
                                <form action="delete_request.php" method="post" onsubmit="return confirmDelete();" style="display:inline;">
                                    <input type="hidden" name="jr_id" value="<?php echo $row['jr_id']; ?>">
                                    <button type="submit" class="delete-button">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endif;?>
                
            <?php elseif($row['jr_type'] === 2): ?>
                <?php if ($row['jr_accept'] == 1):?>
                    <tr>
                        <td colspan="3">
                            <div class="details">
                                <b>Your application as a <?php echo $row['job_list_job'];?> has been accepted.</b>
                                <a href="jobseekerhired.php" onclick="markAsRead(<?php echo $row['jr_id']; ?>)" class="view-button"><span class="eye-icon"></span>View</a>
                                <form action="delete_request.php" method="post" onsubmit="return confirmDelete();" style="display:inline;">
                                    <input type="hidden" name="jr_id" value="<?php echo $row['jr_id']; ?>">
                                    <button type="submit" class="delete-button">
                                        <i class="bx bx-trash"></i> 
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endif;?>
                
            <?php elseif($row['jr_type'] === 3):?>
            <?php else:?>
            <?php endif;?>
        <?php endforeach;?>
    <?php else:?>
        <tr>
        <td colspan="3">
            <div class="details">
                <b>No items yet</b>
            </div>
        </td>
    </tr>
    <?php endif;?>
</table>

<?php include '../jobseeker/jfooter.html'; ?>

<script src="script.js"></script>
</body>
</html>