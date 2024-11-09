<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user details from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Verify current password
    if (password_verify($current_password, $user['password'])) {
        // Update password
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $update_stmt->bind_param('ss', $hashed_new_password, $username);
        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Password changed successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['message'] = "Error: " . $update_stmt->error;
        }
    } else {
        $_SESSION['message'] = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Agricultural Produce</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="farmer_dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_product.php">Add Product</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 class="my-4">Profile</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <!-- Profile Picture -->
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="img-fluid rounded-circle">
        </div>
        <div class="col-md-8">
            <h3><?php echo htmlspecialchars($user['name']); ?></h3>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>

            <!-- Change Password Form -->
            <h4>Change Password</h4>
            <form method="post" action="">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
