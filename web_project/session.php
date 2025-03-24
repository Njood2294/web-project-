
<?php
session_start();
$_SESSION['user_id']=445566;
$_SESSION['user_type']='patient';
//cheack if it is log in 
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: HomePage.php");
    exit();
}


$current_page = basename($_SERVER['PHP_SELF']); 

if ($_SESSION['user_type'] === 'patient' && strpos($current_page, 'Doctor') !== false) {
    header("Location: PatientHomePage.php");
    exit();
} elseif ($_SESSION['user_type'] === 'doctor' && strpos($current_page, 'Patient') !== false) {
   header("Location: DoctorHomePage.php");
  
    exit();
}
?>

