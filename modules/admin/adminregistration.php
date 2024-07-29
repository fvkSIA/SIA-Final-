<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

$users = "SELECT users.*, user_types.id as user_type_id, user_types.description as user_type_desc FROM users
    INNER JOIN user_types ON users.type = user_types.id
    WHERE type != 1 AND verified = 0";

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
                @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .container-fluid {
            padding: 20px;
        }

        .card {
            border-radius: 15px;
            border: none;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 15px 30px;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .table th, .table td {
            vertical-align: middle;
            white-space: nowrap;
            text-align: center;
            font-size: 1rem;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .btn-view, .btn-delete {
            color: white;
            transition: background-color 0.3s ease;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        .btn-view {
            background-color: #28a745;
        }
        
        .btn-view:hover {
            background-color: #218838;
        }
        
        .btn-delete {
            background-color: #dc3545;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<?php 
    $data = [];
    if ($result != null)
        $data = $result->fetch_all(MYSQLI_ASSOC);
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1 class="h4">Applicants</h1>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="applicantsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Name</th>
                                    <th style="width: 10%;">Contact No.</th>
                                    <th style="width: 20%;">Email Address</th>
                                    <th style="width: 20%;">Address</th>
                                    <th style="width: 10%;">Type</th>
                                    <th style="width: 10%;">Applied Date</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data):?>
                                    <?php foreach($data as $user):?>
                                        <tr>
                                            <td><?php echo $user['id'];?></td>
                                            <td><?php echo $user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname'];?></td>
                                            <td><?php echo $user['phone_number'];?></td>
                                            <td><?php echo $user['email'];?></td>
                                            <td><?php echo $user['home_address'];?></td>
                                            <td><?php echo $user['user_type_desc'];?></td>
                                            <td><?php $date = date_create($user['created_at']); echo date_format($date, "M d, Y H:i:s");?></td>
                                            <td>
                                                <a href="adminviewapplicantsdetails.php?id=<?php echo $user['id'];?>" class="btn btn-view btn-sm"><i class="fas fa-eye"></i> View</a>
                                                <a href="delete.php?id=<?php echo $user['id'];?>" class="btn btn-delete btn-sm"><i class="fas fa-trash-alt"></i> Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <!-- Add any pagination or additional controls here -->
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
        $(document).ready(function() {
            let table = new DataTable('#applicantsTable', {
                responsive: true
            });
        });
    </script>

    <script>
        <?php if(isset($_SESSION['delete_success']) && $_SESSION['delete_success']): ?>
        alert("Registration successfully deleted!");
        window.location.href = 'adminregistration.php';
        <?php 
        unset($_SESSION['delete_success']);
        endif; 
        ?>
    </script>
</body>
</html>
