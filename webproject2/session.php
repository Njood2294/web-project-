
<?php
session_start();

//cheack if it is log in 
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: HomePage.html");
    exit();
}


$current_page = basename($_SERVER['PHP_SELF']); 

if ($_SESSION['user_type'] === 'patient' && preg_match('/Doctor|Prescribe/', $current_page)) {
    header("Location: PatientHomePage.php");
    exit();
}
 elseif ($_SESSION['user_type'] === 'doctor' && preg_match('/Patient|Appointment/', $current_page)) {
    header("Location: DoctorHomePage.php");
    exit();
}
?>

