<?php

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin') {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin");
            exit();
        } else {
            $login_error = "Invalid credentials!";
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
            .login-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            input { display: block; margin: 10px 0; padding: 10px; width: 100%; }
            button { padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>Admin Login</h2>
            <?php if (isset($login_error)) echo "<p style='color:red;'>$login_error</p>"; ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>

<?php


$db = new Database();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'], $_POST['table'])) {
    $id = intval($_POST['id']);
    $table = $_POST['table'];
    
    $allowedTables = ['room_post', 'looking_for', 'food'];
    if ($id > 0 && in_array($table, $allowedTables)) {
        $sql = "DELETE FROM `$table` WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Record deleted successfully.');</script>";
        } else {
            echo "<script>alert('Error deleting record: {$conn->error}');</script>";
        }
        
        $stmt->close();
    }
}

function fetchRecords($conn, $table) {
    $sql = "SELECT * FROM `$table`";
    $result = $conn->query($sql);
    
    echo "<h3>" . ucfirst(str_replace('_', ' ', $table)) . "</h3>";
    echo "<table border='1' style='width:100%; border-collapse:collapse;'>
            <tr style='background:#007bff; color:white;'>";
    if ($result->num_rows > 0) {
        $columns = array_keys($result->fetch_assoc());
        foreach ($columns as $column) {
            echo "<th style='padding:10px;'>$column</th>";
        }
        echo "<th>Action</th></tr>";
        
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<tr style='text-align:center;'>";
            foreach ($row as $value) {
                echo "<td style='padding:10px;'>$value</td>";
            }
            echo "<td><form method='post'><input type='hidden' name='table' value='$table'><input type='hidden' name='id' value='" . $row['id'] . "'><button type='submit' style='background:red; color:white; border:none; padding:5px 10px; cursor:pointer;'>Delete</button></form></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='100%' style='text-align:center;'>No records found</td></tr>";
    }
    echo "</table><br>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Records</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <h2>Admin Panel - Manage Records</h2>
    <?php
    fetchRecords($conn, 'room_post');
    fetchRecords($conn, 'looking_for');
    fetchRecords($conn, 'food');
    $db->closeConnection();
    ?>
</body>
</html>
