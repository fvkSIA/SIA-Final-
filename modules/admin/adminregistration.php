<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

$users = "SELECT users.*, user_types.id as user_type_id, user_types.description as user_type_desc FROM users
    INNER JOIN user_types ON users.type = user_types.id
    WHERE type != 1 AND verified = 0";

if ($stmt = $conn->prepare($users)) {
    // $stmt->bind_param("ss", $job_type, $param);
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
                        <h1 class="h4">Applicants</h1>
                        <!-- <div class="search">
                            <input type="text" id="search" class="form-control" placeholder="Search..." onkeyup="searchTable()">
                        </div> -->
                    </div>
                    <div class="card-body table-responsive">
                    <?php if(isset($_SESSION['flash'])):?>
                        <div class="alert alert-success" role="alert">
                            Success!
                        </div>
                        <?php unset($_SESSION["flash"]);?>
                    <?php endif;?>
                    
                        <table id="applicantsTable" class="display nowrap dataTable dtr-inline collapsed">
                            <thead class="">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Name</th>
                                    <th style="width: 10%;">Contact No.</th>
                                    <th style="width: 20%;">Email Address</th>
                                    <th style="width: 20%;">Address</th>
                                    <th style="width: 20%;">Type</th>
                                    <th style="width: 10%;">Applied Date</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data):?>
                                    <?php foreach($data as $user):?>
                                        <tr>
                                            <td><?php echo $user['id'];?></td>
                                            <td><?php echo $user['firstname'];?> <?php echo $user['middlename'];?> <?php echo $user['lastname'];?></td>
                                            <td><?php echo $user['phone_number'];?></td>
                                            <td><?php echo $user['email'];?></td>
                                            <td><?php echo $user['home_address'];?></td>
                                            <td><?php echo $user['user_type_desc'];?></td>
                                            <td><?php $date=date_create($user['created_at']); echo date_format($date,"M d, Y H:i:s");?></td>
                                            <td>
                                                <a href="adminviewapplicantsdetails.php?id=<?php echo $user['id'];?>" class="btn btn-success btn-sm">View</a>
                                                <a href="delete.php?id=<?php echo $user['id'];?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                                
                                <!-- <tr>
                                    <td>2</td>
                                    <td>John Carlo Javalla</td>
                                    <td>09123456789</td>
                                    <td>jcmasarape@gmail.com</td>
                                    <td>6995 Kantunan St. Tondo Manila</td>
                                    <td>05/18/2024</td>
                                    <td>
                                        <a href="adminviewapplicantsdetails.html?id=2" class="btn btn-success btn-sm">View</a>
                                        <button class="btn btn-danger btn-sm" onclick="removeApplicant(this)"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Ken Cruz</td>
                                    <td>1234567890</td>
                                    <td>kenmasarap@example.com</td>
                                    <td>123 Main St, City</td>
                                    <td>06/10/2024</td>
                                    <td>
                                        <a href="adminviewapplicantsdetails.html" class="btn btn-success btn-sm">View</a>
                                        <button class="btn btn-danger btn-sm" onclick="removeApplicant(this)"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr> -->
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
    <script>
        let table = new DataTable('#applicantsTable', {
            responsive: true
        });
        // function removeApplicant(btn) {
        //     var row = btn.closest('tr');
        //     row.remove();
        // }

        // function searchTable() {
        //     var input, filter, table, tr, td, i, j, txtValue;
        //     input = document.getElementById("search");
        //     filter = input.value.toUpperCase();
        //     table = document.getElementById("applicantsTable");
        //     tr = table.getElementsByTagName("tr");
            
        //     for (i = 1; i < tr.length; i++) {
        //         tr[i].style.display = "none";
        //         td = tr[i].getElementsByTagName("td");
        //         for (j = 0; j < td.length; j++) {
        //             if (td[j]) {
        //                 txtValue = td[j].textContent || td[j].innerText;
        //                 if (txtValue.toUpperCase().indexOf(filter) > -1) {
        //                     tr[i].style.display = "";
        //                     break;
        //                 }
        //             }
        //         }
        //     }
        // }
    </script>
</body>
</html>
