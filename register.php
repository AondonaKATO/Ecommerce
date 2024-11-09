<?php
include 'db.php';
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $type = $_POST['type']; // 'farmer' or 'individual'

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
    } else {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $username, $hashed_password, $email, $type);

        // Execute statement and check for errors
        if ($stmt->execute()) {
            $_SESSION['message'] = "Registration successful!";
            header("Location: index.php"); // Redirect to the homepage
            exit();
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="my-4">Register</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="form-group">
            <label for="type">Register as:</label>
            <select class="form-control" id="type" name="type" required>
                <option value="individual">Individual</option>
                <option value="farmer">Farmer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <div class="mt-3">
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
