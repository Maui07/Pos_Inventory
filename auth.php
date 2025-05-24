<?php
session_start();

// Database connection configuration
$host = 'localhost';
$db   = 'miakape';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Retrieve form data
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

// Validate input
if (empty($username) || empty($password) || empty($role)) {
    echo "<script>alert('All fields are required.'); window.history.back();</script>";
    exit;
}

// Query the user
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
$stmt->execute([$username, $role]);
$user = $stmt->fetch();

$valid_users = [
    'admin' => [
        'password' => 'admin123',
        'role' => 'Admin'
    ],
    'cashier' => [
        'password' => 'cashier123',
        'role' => 'Cashier'
    ]
];

if (isset($valid_users[$username]) && 
    $valid_users[$username]['password'] === $password && 
    $valid_users[$username]['role'] === $role) {

    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    // Redirect based on role
    if ($role === 'Admin') {
        header("Location: admin.php");
        exit;
    } elseif ($role === 'Cashier') {
        header("Location: cashier.php");
        exit;
    } else {
        echo "<script>alert('Unknown role.'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid credentials.'); window.history.back();</script>";
    exit;
}

