<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: error.php"); // Redirect to error page
    exit();
}
$userId = $_SESSION["userId"];
$userName = $_SESSION["userName"];
$userEmail = $_SESSION["userEmail"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="css/profile.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include 'header.php'; ?>
     <div class="container mt-5">
        <div class="row justify-content-start">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="profile-card shadow-sm p-4 rounded">
                    <div class="profile-header d-flex align-items-center">
                        <h2 class="title mb-0">Profile</h2>
                        <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 

    <dotlottie-player src="https://lottie.host/136ae463-ee34-4b87-b500-0e57b858e856/Sc9qVQkGwL.json" background="transparent" speed="1" style="width: 30px; height: 30px;" loop autoplay></dotlottie-player>
                    </div>
                    <div class="mt-4">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <div><?php echo htmlspecialchars($userName); ?></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">E-mail</label>
                            <div><?php echo htmlspecialchars($userEmail); ?></div>
                        </div>
                       
                        <div class="form-group">
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#logoutModal">LogOut</button>
                        </div>
                    </div>
                </div>
                <div id="logout-message" class="logout-message" style="display: none;">
                    <div>
                        <h1>You have been logged out</h1>
                        <p>Thank you for visiting. You can <a href="#" class="btn btn-primary" onclick="location.reload();">Log In Again</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel"><span style="color: black">Confirm Logout</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span style="color: black">Are you sure you want to log out?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="log_out_backend.php" method="POST">
                        <button type="submit" class="btn btn-primary" name="logout" value="Log Out">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- bootstrap -->
</body>
</html>