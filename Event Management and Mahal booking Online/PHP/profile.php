<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Retrieve user information from the signup table based on email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, fetch profile information
        $user = $result->fetch_assoc();
        $username = $user['username'];
        $email = $user['email'];

        // Now you can display the user's profile information
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <!-- Add any additional profile information here -->
</body>
</html>
<?php
    } else {
        // User not found
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    // If user is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}
?>
