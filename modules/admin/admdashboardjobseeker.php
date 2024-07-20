<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

$users = "SELECT users.*, user_types.id as user_type_id, user_types.description as user_type_desc, worker_type.id as worker_type_id, worker_type.description as worker_type_desc FROM users
    INNER JOIN user_types ON users.type = user_types.id
    LEFT JOIN worker_type ON users.worker_type_id = worker_type.id
    WHERE type = 2 AND verified = 1";

if ($stmt = $conn->prepare($users)) {
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
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icon Links -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container-fluid {
            padding: 20px;
        }

        .status select {
            padding: 7px;
            font-size: 15px;
            border-radius: 5px;
            border: 1px solid black;
            background-color: #fffaff;
            color: black;
        }

        .pagination {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 20px;
        }

        .pagination button, .pagination input {
            margin: 0 5px;
            padding: 5px 10px;
        }

        .table th, .table td {
            vertical-align: middle;
            white-space: nowrap;
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1 class="h4">Jobseekers</h1>

                        <div class="search">
                            <a class="btn btn-primary" href="admin-jobseek-signup.php">&#43; ADD NEW JOBSEEKER</a>
                            <button class="btn btn-secondary" onclick="printJobseekers()">Print Jobseekers List</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="applicantsTable" class="display nowrap dataTable dtr-inline collapsed">
                            <thead class="">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th>Availability</th>
                                    <th>Work Type</th>
                                    <th style="width: 15%;">Name</th>
                                    <th style="width: 10%;">Contact No.</th>
                                    <th style="width: 20%;">Email Address</th>
                                    <th style="width: 20%;">Address</th>
                                    <th style="width: 10%;">Applied Date</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data):?>
                                    <?php foreach($data as $user):?>
                                        <tr>
                                            <td><?php echo $user['id'];?></td>
                                            <td class="text-center text-white">
                                                <?php if($user['availability'] == 1):?>
                                                    <span class="badge bg-success">Available</span>
                                                <?php else:?>
                                                    <span class="badge bg-danger">Not Available</span>
                                                <?php endif;?>
                                            </td>
                                            <td><?php echo $user['worker_type_desc'];?></td>
                                            <td><?php echo $user['firstname'];?> <?php echo $user['middlename'];?> <?php echo $user['lastname'];?></td>
                                            <td><?php echo $user['phone_number'];?></td>
                                            <td><?php echo $user['email'];?></td>
                                            <td><?php echo $user['home_address'];?></td>
                                            <td><?php $date=date_create($user['created_at']); echo date_format($date,"M d, Y H:i:s");?></td>
                                            <td>
                                                <a href="userviewprofile.php?id=<?php echo $user['id'];?>" class="btn btn-success btn-sm">View</a>
                                                <!-- <button class="btn btn-danger btn-sm" onclick="removeApplicant(this)"><i class="fas fa-trash-alt"></i></button> -->
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                        <div class="status mb-3">
                            <!-- <select class="form-control w-auto">
                                <option>EMPLOYER</option>
                                <option>JOBSEEKER</option>
                            </select> -->
                        </div>
                        <div class="d-flex justify-content-end">
                            <!-- <nav>
                                <ul class="pagination">
                                    <li class="page-item"><button class="page-link">Previous</button></li>
                                    <li class="page-item"><button class="page-link">Next</button></li>
                                </ul>
                            </nav> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
    <script>
        let table = new DataTable('#applicantsTable', {
            responsive: true
        });

        function printJobseekers() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Get table data excluding the Action column
            let data = [];
            document.querySelectorAll('#applicantsTable tbody tr').forEach(row => {
                let rowData = [];
                row.querySelectorAll('td').forEach((cell, index) => {
                    if (index < 8) { // Exclude the last column (Action)
                        rowData.push(cell.innerText);
                    }
                });
                data.push(rowData);
            });

            // Define table headers excluding the Action column
            let headers = [['#', 'Availability', 'Work Type', 'Name', 'Contact No.', 'Email Address', 'Address', 'Applied Date']];

            // Add table to PDF
            doc.autoTable({
                head: headers,
                body: data,
                startY: 10,
                theme: 'grid',
                headStyles: { fillColor: [22, 160, 133] },
                alternateRowStyles: { fillColor: [240, 240, 240] },
                styles: { cellPadding: 3, fontSize: 8 },
            });

            // Save the PDF
            doc.save('Jobseekers_List.pdf');
        }
    </script>
</body>
</html>
