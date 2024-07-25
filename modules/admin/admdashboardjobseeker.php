<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Jobseekers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    
    <style>
        body {
            background-color: #f8f9fa;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
        }
        .container-fluid {
            padding: 20px 40px;
            width: 100%;
            max-width: 100%;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 100%;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 20px;
        }
        .card-header h5 {
            margin: 0;
        }
        .card-body {
            padding: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 0.75rem 1rem;
        }
        .table th {
            font-size: 0.95rem;
            font-weight: 600;
            white-space: nowrap;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        .btn {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-light {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .btn-light:hover {
            background-color: #e2e6ea;
        }
        .badge {
            padding: 0.5em 0.75em;
        }
        .btn-view {
            width: 100px;
            height: auto;
            padding: 5px 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>

<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

$users = "SELECT users.*, user_types.id as user_type_id, user_types.description as user_type_desc FROM users
    INNER JOIN user_types ON users.type = user_types.id
    WHERE type = 2 AND verified = 1";

$data = [];
if ($stmt = $conn->prepare($users)) {
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result != null) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
}
$conn->close();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Jobseekers</h5>
                    <div>
                        <a class="btn btn-light btn-sm me-2" href="admin-emp-signup.php">
                            <i class="bx bx-plus me-1"></i>Add New Jobseeker
                        </a>
                        <button class="btn btn-light btn-sm" onclick="printJobseekers()">
                            <i class="bx bx-printer me-1"></i>Print Jobseekers List
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="jobseekersTable" class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Contact No.</th>
                                    <th scope="col">Email Address</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Applied Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data): ?>
                                    <?php foreach($data as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo $user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname']; ?></td>
                                            <td><?php echo $user['phone_number']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><?php echo $user['home_address']; ?></td>
                                            <td><?php echo $user['user_type_desc']; ?></td>
                                            <td><?php echo date('M d, Y H:i:s', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <a href="userviewprofile.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm btn-view">
                                                    <i class="bx bx-show-alt me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<script>
    $(document).ready(function() {
        $('#jobseekersTable').DataTable({
            ordering: false,
            responsive: true,
            lengthChange: false
        });
    });

    function printJobseekers() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Get table data excluding the Action column
        let data = [];
        document.querySelectorAll('#jobseekersTable tbody tr').forEach(row => {
            let rowData = [];
            row.querySelectorAll('td').forEach((cell, index) => {
                if (index < 7) { // Exclude the last column (Action)
                    rowData.push(cell.innerText);
                }
            });
            data.push(rowData);
        });

        // Define table headers excluding the Action column
        let headers = [['#', 'Name', 'Contact No.', 'Email Address', 'Address', 'Type', 'Applied Date']];

        // Add table to PDF
        doc.autoTable({
            head: headers,
            body: data,
            startY: 10,
            theme: 'grid',
            headStyles: { fillColor: [22, 160, 133] },
            alternateRowStyles: { fillColor: [239, 242, 245] },
            tableLineColor: [44, 62, 80],
            tableLineWidth: 0.1,
        });

        doc.save('jobseekers.pdf');
    }
</script>

</body>
</html>
