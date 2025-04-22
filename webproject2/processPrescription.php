<?php
 include "session.php";



// 1. Get form data
$patient_id     = $_POST['patient_id']     ?? '';
$appointment_id = $_POST['appointment_id'] ?? '';
$selectedMeds   = $_POST['medications']    ?? []; // array of medication IDs

if (empty($patient_id) || empty($appointment_id)) {
    die("Missing patient_id or appointment_id in form submission.");
}

// 2. Connect to DB
$servername = "sql101.infinityfree.com";
$dbUsername = "if0_38801004";
$dbPassword = "100200300Ll";
$dbName     = "if0_38801004_it329project";

$connection = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// 3. Update the appointment status to 'Done'
$updateSql = "UPDATE appointment SET status='Done' WHERE id='$appointment_id'";
mysqli_query($connection, $updateSql);

// 4. Insert a new row in `prescription` for each medication selected
foreach ($selectedMeds as $medID) {
    // $medID is one of the IDs from the `medication` table
    $insertSql = "INSERT INTO prescription (AppointmentID, MedicationID)
                  VALUES ('$appointment_id', '$medID')";
    mysqli_query($connection, $insertSql);
}

// 5. (Optional) Close the connection
mysqli_close($connection);

// 6. Redirect back to doctor's homepage
header("Location: DoctorHomePage.php");
exit();
