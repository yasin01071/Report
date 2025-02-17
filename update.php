<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You need to login to access this page.");
}

// Database connection
$host = 'localhost';
$dbname = 'user_auth';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];

    $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(['username' => $newUsername, 'email' => $newEmail, 'id' => $_SESSION['user_id']]);
        echo "Profile updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch current user data
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
</head>
<body>
    <h2>Update Profile</h2>
    <form method="POST" action="update.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>
        
        <button type="submit">Update</button>
    </form>
</body>
</html>