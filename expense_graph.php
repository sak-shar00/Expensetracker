<?php
// expense_graph.php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch total expenses grouped by month
$query = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(amount) AS total 
          FROM expenses 
          GROUP BY DATE_FORMAT(date, '%Y-%m') 
          ORDER BY month";

$result = $conn->query($query);
$months = [];
$totals = [];

while ($row = $result->fetch_assoc()) {
    $months[] = $row['month'];
    $totals[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Comparison</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js Library -->
</head>
<body>
<div class="container mt-5">
    <h2>Expense Comparison by Month</h2>
    <canvas id="expenseChart" width="400" height="200"></canvas>
    <a href="admin.php" class="btn btn-secondary mt-3">Back to Admin</a>
</div>

<script>
    // Prepare data for Chart.js
    const ctx = document.getElementById('expenseChart').getContext('2d');
    const expenseChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>, // Months from PHP
            datasets: [{
                label: 'Total Expenses',
                data: <?php echo json_encode($totals); ?>, // Total expenses from PHP
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount Spent'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
