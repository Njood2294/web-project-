<?php
ini_set('display_errors','1');
include "session.php";
header('Content-Type: text/plain');

     

          $host = "sql101.infinityfree.com";
        $username = "if0_38801004"; 
        $password = "100200300Ll";      
        $databasename = "if0_38801004_it329project";
        $connection= mysqli_connect($host, $username, $password, $databasename);

        $error=mysqli_connect_error();
        if($error!=null){
        $out="<p>Unable to connect to database</p>" .$error;
        exit($out);
        }
        
        
     $patient_id = $_SESSION['user_id'];


if (isset($_POST['id'])) {
    $appointment_id = $_POST['id'];

    
    $sql = "DELETE FROM Appointment WHERE id = $appointment_id AND PatientID = $patient_id";

    
    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo "true";
    } else {
        echo "false";
    }
} else {
    echo "false";
}
    
    

        
        
?>
