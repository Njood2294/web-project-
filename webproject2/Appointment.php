<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<?php
 include "session.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hope clinic</title>
<link rel="stylesheet" href="Css-PatientHomePage.css" id="main-theme-stylesheet"> 
</head>
<body>
    <main id="Appointment"> 
   <h1 >Book an Appointment</h1>
   <form id="form1" action="Appointment.php" method="POST">
    <label>Select Speciality: </label>
    <select name="speciality" id="speciality">
        <?php
        

    $connect = mysqli_connect("localhost", "root", "root", "IT329Project");

    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $patient_id = $_SESSION['user_id']; 
                $sql="SELECT * FROM Speciality";
                $result2=mysqli_query($connect,$sql);
                while($row2=mysqli_fetch_assoc($result2))
                echo "<option value='" .$row2['id']. "'>" .$row2['speciality']. "</option>";


    ?>
    </select>
    <input type="submit" id="submit1">
   </form>

   <form id="form2" action="Appointment.php" method="POST">
    <label>Select Doctor: </label>
    <select id="doctor" name="doctor">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET"){
                $sql="SELECT * FROM Doctor";
                $result=mysqli_query($connect,$sql);
                while($row=mysqli_fetch_assoc($result)){
                    $sql="SELECT speciality FROM Speciality WHERE id='".$row['SpecialityID']."'";
                    $result2=mysqli_query($connect,$sql);
                    $row2=mysqli_fetch_assoc($result2);

                    echo "<option value='" .$row['id']. "'>" .$row['firstName']." ".$row['lastName']."-".$row2['speciality']. "</option>";
                
                }
            }
            
            if($_SERVER["REQUEST_METHOD"]=="POST")
            {
                if(isset($_POST["speciality"]))
                {
                     $spe=$_POST["speciality"];
                     $sql="SELECT * FROM Doctor WHERE SpecialityID='$spe'";
                     $result=mysqli_query($connect,$sql);
                     while($row=mysqli_fetch_assoc($result)){
                     echo "<option value='" .$row['id']. "'>" .$row['firstName']." ".$row['lastName']."-".$row2['speciality']. "</option>";
                
                }
                    
                }
            }
            if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["doctor"]) && isset($_POST["date"]) && isset($_POST["time"])  && isset($_POST["reason"]))
            {
                $n=$_POST["doctor"];
                $d=$_POST["date"];
                $t=$_POST["time"];
                $r=$_POST["reason"];
                
                
                $sql="INSERT INTO appointment(PatientID,DoctorID,date,time,reason,status) VALUES('$patient_id','$n','$d','$t','$r','Pending')";
                if (mysqli_query($connect, $sql)) {
                    header("Location: PatientHomePage.php?message=Appointment booked successfully");
                    exit();
                } else {
                    echo "Error: " . mysqli_error($connect);
                }
            }
        ?>
    </select>
    <br>
    <label id="date" >Select date 
        <input type="date" name="date">
    </label>
    <br>
    <label id="time" >Select time 
        <input type="time" name='time'>
    </label><br>
    <label id="reason">
        Reason for Visit:<br>
        <textarea name='reason'>

        </textarea>
    </label>
    <input type="submit" id="submit2" >
   </form>
</main>
</body>
</html>
