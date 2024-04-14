<?php
session_start(); 

include 'db_config.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_note'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    
    $notes_table_name = $username . "_notes";

    
    $title = mysqli_real_escape_string($conn, $title);
    $content = mysqli_real_escape_string($conn, $content);

   
    $sql = "INSERT INTO `$notes_table_name` (`title`, `content`) VALUES ('$title', '$content')";
    $result = $conn->query($sql);

    if ($result) {
        $message = "Note created successfully";
    } else {
        $message = "Error creating note: " . $conn->error;
    }
}


if (isset($_GET['delete'])) {
    $note_id = $_GET['delete'];

   
    $notes_table_name = $username . "_notes";

    
    $sql = "DELETE FROM `$notes_table_name` WHERE `id` = $note_id";
    $result = $conn->query($sql);

    if ($result) {
        $message = "Note deleted successfully";
    } else {
        $message = "Error deleting note: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_note'])) {
    $note_id = $_POST['note_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

 
    $notes_table_name = $username . "_notes";

    
    $title = mysqli_real_escape_string($conn, $title);
    $content = mysqli_real_escape_string($conn, $content);

    $sql = "UPDATE `$notes_table_name` SET `title`='$title', `content`='$content' WHERE `id`='$note_id'";
    $result = $conn->query($sql);

    if ($result) {
        $message = "Note updated successfully";
    } else {
        $message = "Error updating note: " . $conn->error;
    }
}

$notes_table_name = $username . "_notes";
$sql = "SELECT * FROM `$notes_table_name`";
$result = $conn->query($sql);

$notes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <h3>Create a New Note</h3>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="title" placeholder="Title" style="width: 300px;" required><br>
            <textarea name="content" placeholder="Content" rows="4" style="width: 300px;" required></textarea><br>
            <button type="submit" name="create_note" style="width: 100px; margin-top: 10px;">Create Note</button>
        </form>
        <h3>Your Notes</h3>
        <?php if (!empty($notes)) : ?>
            <ul>
                <?php foreach ($notes as $note) : ?>
                    <li>
                        <strong><?php echo $note['title']; ?></strong>
                        <p><?php echo $note['content']; ?></p>
                        <button class="edit-btn" style="width: 100px; margin-top: 10px;">Edit</button>
                        <form class="edit-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: none;">
                            <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                            <input type="text" name="title" value="<?php echo $note['title']; ?>" style="width: 300px;" required><br>
                            <textarea name="content" rows="2" style="width: 300px;" required><?php echo $note['content']; ?></textarea><br>
                            <button type="submit" name="edit_note" style="width: 100px; margin-top: 10px;">Save</button>
                        </form>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" style="display: inline;">
                            <input type="hidden" name="delete" value="<?php echo $note['id']; ?>">
                            <button type="submit" style="width: 100px; margin-top: 10px;">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No notes found.</p>
        <?php endif; ?>
        <p><a href="logout.php">Logout</a></p>
    </div>
    <script>
        document.querySelectorAll('.edit-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                var editForm = this.nextElementSibling;
                if (editForm.style.display === 'none') {
                    editForm.style.display = 'block';
                } else {
                    editForm.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
