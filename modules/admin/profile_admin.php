<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #495057;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .btn-primary {
            border-radius: 50px;
        }
        .modal-content {
            border-radius: 12px;
        }
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        .modal-footer {
            border-top: 1px solid #dee2e6;
        }
        .close {
            color: #6c757d;
            font-size: 1.5rem;
            font-weight: bold;
            opacity: 0.7;
        }
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            opacity: 1;
        }
        .alert {
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Hi, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h5>
                <p class="card-text"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <a href="userviewprofile.php?id=<?php echo htmlspecialchars($_SESSION['user_id']); ?>" class="btn btn-primary float-end">Edit Profile</a>
            </div>
        </div>
        <?php if(isset($_SESSION['flash'])): ?>
            <div class="alert alert-success my-3" role="alert">
                <?php echo htmlspecialchars($_SESSION['flash']); ?>
            </div>
            <?php unset($_SESSION["flash"]); ?>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div id="editModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="admin_profile.html" width="100%" height="400" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editProfileBtn = document.querySelector('.btn-primary');
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));

            editProfileBtn.addEventListener('click', () => {
                editModal.show();
            });
        });
    </script>
</body>
</html>
