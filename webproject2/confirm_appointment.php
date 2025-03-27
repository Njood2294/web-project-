<?php

include "session.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Database connection
$connection = mysqli_connect("localhost", "root", "root", "IT329Project");
if (!$connection) {
    die("<p>Connection failed: " . mysqli_connect_error() . "</p>");
}

// Check if the appointment ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p>Error: No appointment ID provided.</p>");
}

$appointment_id = $_GET['id'];

// Update the appointment status to "Confirmed"
$sql = "UPDATE Appointment SET status = 'Confirmed' WHERE id = '$appointment_id'";

if (mysqli_query($connection, $sql)) {
    // Redirect to the doctor's homepage after updating the status
    header("Location: DoctorHomePage.php");
    exit();
} else {
    echo "<p>Error updating appointment: " . mysqli_error($connection) . "</p>";
}

mysqli_close($connection);
?>
