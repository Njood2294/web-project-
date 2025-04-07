
<html>
    <head>
          <meta charset="UTF-8">
    <title>Hope clinic</title>
<link rel="stylesheet" href="Css-PatientHomePage.css" id="main-theme-stylesheet"> 
    </head>
    <body>
        <?php
       
        ini_set('display_errors','1');
        include "session.php";
        //connection
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
        ///////////////////
        
        //cheak paitent id and display First name, last name and email
        $patient_id = $_SESSION['user_id'];
       
        $sql = "SELECT * FROM Patient WHERE id = $patient_id";
        $result = mysqli_query($connection,$sql);
        $patient_info = mysqli_fetch_assoc($result);
        
        ?>
        
        
  <a href="logout.php" id="log">Log out</a> 
 <main id="PatientHomePage">
       
  <h2>Welcom <?php echo $patient_info['firstName']; ?></h2>
  <div id ="info">
   
    
    
    
    <div>
    <p>Name: <?php echo $patient_info['firstName']; ?>  <?php echo $patient_info['lastName']; ?></p>
    <p>Id:<?php echo $patient_info['id']; ?> </p>
    </div>
    <div>
    <p>DoB:<?php echo $patient_info['DoB']; ?> </p>
    <p>Email: <?php echo $patient_info['emailAddress']; ?></p>
</div>
         <div>
    <p>Gender:<?php echo $patient_info['Gender']; ?> </p>
    </div>

    
    
    
    
    
    
    
    
    
    
</div>        
<a href="Appointment.php" id="Book">Book an appointment</a>  

<table>
    <thead>
        <tr>
            <th>Time</th>
            <th>Date</th>
            <th>Doctor's Name</th>
            <th>Doctor's Photo</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT Appointment.*, Doctor.firstName AS doctorFirstName, 
               Doctor.lastName AS doctorLastName, Doctor.uniqueFileName 
        FROM Appointment 
        JOIN Doctor ON Appointment.DoctorID = Doctor.id 
        WHERE Appointment.PatientID = $patient_id 
        ORDER BY date, time";

$disAppointments = mysqli_query($connection, $sql);
while ($row = mysqli_fetch_assoc($disAppointments)) {
    
    $time_12h = date("h:i A", strtotime($row['time']));
    $doctor_image = !empty($row['uniqueFileName']) ? "uploads/" . htmlspecialchars($row['uniqueFileName']) : "uploads/default.jpg";
    // التحقق مما إذا كان status يساوي "Confirmed" أو "Pending"
    if ($row['status'] == "Confirmed" || $row['status'] == "Pending") {
        echo "<tr>
            <td>{$time_12h}</td>
            <td>{$row['date']}</td>
            <td>{$row['doctorFirstName']} {$row['doctorLastName']}</td>
            <td><img src='{$doctor_image}' alt='Doctor Image' width='50'></td>
            <td>{$row['status']}</td>
            <td><a href='cancel.php?id={$row['id']}'>Cancel</a></td>
        </tr>";
    }
}
        
        ?>
    </tbody>
</table>
   </main>
    
        
        
        
        
    </body>
</html>
