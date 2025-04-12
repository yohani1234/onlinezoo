<?php
// filepath: c:\xampp\htdocs\myzoo\zoo\ticket.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myzoo";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $plan_name = $_POST['plan_name'];
    $plan_price = $_POST['plan_price'];
    $user_email = $_POST['user_email'];
    $voucher_code = $_POST['voucher_code'];
    $payment_method = $_POST['payment_method'];

    // Check if the user provided a voucher code
    if (!empty($voucher_code)) {
        // Validate the voucher code
        if ($voucher_code === "12345") {
            // Generate a unique ticket code
            $ticket_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));

            // Insert ticket into the database
            $sql = "INSERT INTO tickets (ticket_code, plan_name, plan_price, user_email, voucher_code, payment_method) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $ticket_code, $plan_name, $plan_price, $user_email, $voucher_code, $payment_method);

            if ($stmt->execute()) {
                echo "<h2 style='color: green; text-align: center;'>Ticket generated successfully using voucher! Your ticket code is: <strong>$ticket_code</strong></h2>";
            } else {
                echo "<h2 style='color: red; text-align: center;'>Error: " . $stmt->error . "</h2>";
            }
        } else {
            echo "<h2 style='color: red; text-align: center;'>Invalid voucher code. Please try again.</h2>";
        }
    } else {
        // If no voucher code is provided, proceed with payment
        if (!empty($payment_method)) {
            // Generate a unique ticket code
            $ticket_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));

            // Insert ticket into the database
            $sql = "INSERT INTO tickets (ticket_code, plan_name, plan_price, user_email, voucher_code, payment_method) VALUES (?, ?, ?, ?, NULL, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $ticket_code, $plan_name, $plan_price, $user_email, $payment_method);

            if ($stmt->execute()) {
                echo "<h2 style='color: green; text-align: center;'>Ticket purchased successfully! Your ticket code is: <strong>$ticket_code</strong></h2>";
                echo "<p style='text-align: center;'>Payment Method: <strong>$payment_method</strong></p>";
            } else {
                echo "<h2 style='color: red; text-align: center;'>Error: " . $stmt->error . "</h2>";
            }
        } else {
            echo "<h2 style='color: red; text-align: center;'>Please select a payment method to buy the ticket.</h2>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>