<?php
session_start();
include 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
    $expense = $_POST['expense']; // Gets the expense item name
    $amount = $_POST['amount']; // Gets the amount value
    $date = $_POST['date']; // Gets the date of the expense

    // Insert the expense into the database
    $stmt = $conn->prepare("INSERT INTO expenses (user_id, expense, amount, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $expense, $amount, $date);
    if ($stmt->execute()) {
        echo "<p class='alert alert-success'>Expense added successfully.</p>";
    } else {
        echo "<p class='alert alert-danger'>Failed to add expense.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Add Expense</h3>
    <form method="POST">
        <div class="form-group">
            <label for="expense">Expense Item:</label>
            <select class="form-control" id="expense" name="expense" required>
                <option value="Food">Food</option>
                <option value="Transport">Transport</option>
                <option value="Rent">Rent</option>
                <option value="Utilities">Utilities</option>
                <option value="Entertainment">Entertainment</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Expense</button>
        <a href="admin.php" class="btn btn-secondary">Back to Admin</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
