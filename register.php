<?php
include 'db_config.php';

$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    

    $sql = "INSERT INTO `users`(`username`, `password`, `email`) VALUES ('$username', '$password', '$email')";
    if ($conn->query($sql) === TRUE) {

        $notes_table_name = $username . "_notes";
        $sql = "CREATE TABLE IF NOT EXISTS `$notes_table_name` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `content` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) !== TRUE) {
            $message = "Error creating table: " . $conn->error;
        } else {
         
            header("Location: login.php");
            exit;
        }
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Registration</h2>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>