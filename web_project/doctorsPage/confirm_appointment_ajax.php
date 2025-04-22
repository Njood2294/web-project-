<?php
include "session.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    $connection = mysqli_connect("localhost", "root", "root", "IT329Project");

    if (!$connection) {
        echo json_encode(false);
        exit;
    }

    $sql = "UPDATE Appointment SET status = 'Confirmed' WHERE id = '$appointment_id'";

    if (mysqli_query($connection, $sql)) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }

    mysqli_close($connection);
}
?>
