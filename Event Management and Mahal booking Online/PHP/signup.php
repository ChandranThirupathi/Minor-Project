<?php
session_start();  // Start the session at the beginning of the script

$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "user_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle sign up
function handleSignUp($conn) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $repeat_password = password_hash($_POST['repeat_password'], PASSWORD_DEFAULT); // Hashing the repeat password as well
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, repeat_password, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $repeat_password, $email);

    if ($stmt->execute() === TRUE) {
        header("Location: index.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to handle sign in
function handleSignIn($conn) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if ($hashed_password && password_verify($password, $hashed_password)) {
        $_SESSION['email'] = $email;  // Store email in session
        header("Location: home.html");
    } else {
        echo "Invalid email or password";
    }

    $stmt->close();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sign_in'])) {
        handleSignIn($conn);
    } elseif (isset($_POST['sign_up'])) {
        handleSignUp($conn);
    }
}

$conn->close();
?>
