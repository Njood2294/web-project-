<?php
ini_set('display_errors','1');
include "session.php";
 $host = "localhost";
        $username = "root"; 
        $password = "root";      
        $databasename = "it329project";
        $connection= mysqli_connect($host, $username, $password, $databasename);

        $error=mysqli_connect_error();
        if($error!=null){
        $out="<p>Unable to connect to database</p>" .$error;
        exit($out);
        }
        
        
     $patient_id = $_SESSION['user_id'];


if (isset($_GET['id'])) {
    $appointment_id =$_GET['id']; 

    
    $sql = "DELETE FROM Appointment WHERE id = $appointment_id AND PatientID = $patient_id";

    if (mysqli_query($connection, $sql)) {
        header("Location: PatientHomePage.php");
        exit();
    } else {
        echo "error happened";
    }
}  
        
        
?>
