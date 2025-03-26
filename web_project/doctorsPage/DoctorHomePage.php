<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
       
    <title>Hope clinic</title>
     <link rel="stylesheet" href="DoctorHomePage.css" id="main-theme-stylesheet"> 
        <title></title>
    </head>
    <body>
        <?php
        
        include "session.php";
        
        
         error_reporting(E_ALL) ;
        ini_set('display_errors', 1) ;
        ini_set('log_errors', 1) ;
        
        $connection= mysqli_connect("localhost","root","root","IT329Project");
        if($error=mysqli_connect_error()){
            $out="<p>there is an error </p>".$error;
        exit($out);} 
        
        $doctor_id = $_SESSION['user_id'];
       
        $sql = "SELECT * FROM Doctor WHERE id = '$doctor_id' ";
        $result = mysqli_query($connection,$sql);
        $doctor_info = mysqli_fetch_assoc($result);
        
        
        ?>
        
        <a href="Homepage/HomePage.html" id="log">Log out</a> 
        <main id="PatientHomePage">
       
  <h2>Welcome <?php echo $doctor_info['firstName']; ?></h2>
  <div id ="info">
    <div>
    <p>Name: <?php echo $doctor_info['firstName']." ".$doctor_info['lastName']; ?> </p>
    <p>Id: <?php echo $doctor_info['id']; ?></p>
    </div>
    <div>
        <?php 
        $specialityid = $doctor_info['SpecialityID'] ;
        
        $sql = "SELECT speciality FROM Speciality WHERE id = '$specialityid' ";
        $result2 = mysqli_query($connection,$sql);
        $specialityname = mysqli_fetch_assoc($result2);
        
        ?>
    <p> Speciality: <?php echo $specialityname['speciality']; ?> </p>
    <p>Email: <?php echo $doctor_info['emailAddress']; ?></p>
</div>
</div>        
      
         <h3>Upcoming Appointments</h3>
        <table border="1">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Reason for Visit</th>
                <th>Status</th>
                
            </tr>

            <?php
            // Fetch upcoming appointments for the doctor (Pending or Confirmed)
            $sql = "SELECT Appointment.id, Appointment.date, Appointment.time, Appointment.reason, Appointment.status, 
                           Patient.firstName, Patient.lastName, Patient.Gender, Patient.DoB, Patient.id AS PatientID
                    FROM Appointment 
                    JOIN Patient ON Appointment.PatientID = Patient.id 
                    WHERE Appointment.DoctorID = '$doctor_id' AND (Appointment.status = 'pending' OR Appointment.status = 'confirmed') ";

            $appointments = mysqli_query($connection, $sql);

            if (!$appointments) {
                die("<p>Query failed: " . mysqli_error($connection) . "</p>");
            }

            if (mysqli_num_rows($appointments) > 0) {
                while ($row = mysqli_fetch_assoc($appointments)) {
                    // Calculate age from date of birth
                    $dob = new DateTime($row['DoB']);
                    $today = new DateTime();
                    $age = $today->diff($dob)->y;

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firstName'] . " " . $row['lastName']) . "</td>";
                    echo "<td>" . htmlspecialchars($age) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status'])."<br>" ;
                   
                    // Action column
                    if ($row['status'] == 'Pending') {
                        echo "<a href='confirm_appointment.php?id=" . htmlspecialchars($row['id']) . "'>Confirm</a>";
                    } elseif ($row['status'] == 'Confirmed') {
                        echo "<a href='Prescribe.php?appointment_id=" . htmlspecialchars($row['id']) ."&pateint_id=".htmlspecialchars($row['PatientID']). "'>Prescribe</a>";
                        
                    } else {
                        echo "-";
                    }
                   echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No upcoming appointments.</td></tr>";
            }

            
            ?>
        </table>
  
   <h3>Your Patients</h3>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Medications</th>
            </tr>
        <tbody>
            <?php
            // Query to get all patients who had a "Done" appointment with the doctor
            $sql = "SELECT DISTINCT Patient.id, Patient.firstName, Patient.lastName, Patient.Gender, Patient.DoB
                    FROM Appointment 
                    JOIN Patient ON Appointment.PatientID = Patient.id
                    WHERE Appointment.DoctorID = '$doctor_id' 
                    AND Appointment.status = 'Done' ";

            $patients = mysqli_query($connection, $sql);

            if (!$patients) {
                die("<p>Query failed: " . mysqli_error($connection) . "</p>");
            }

            if (mysqli_num_rows($patients) > 0) {
                while ($row = mysqli_fetch_assoc($patients)) {
                    // Calculate age from DoB
                    $dob = new DateTime($row['DoB']);
                    $today = new DateTime();
                    $age = $today->diff($dob)->y;

                    // Retrieve medications prescribed to this patient
                    $patient_id = $row['id'];
                    $medications_query = "SELECT DISTINCT Medication.MedicationName
                                          FROM Prescription
                                          JOIN Medication ON Prescription.MedicationID = Medication.id
                                          JOIN Appointment ON Prescription.AppointmentID = Appointment.id
                                          WHERE Appointment.PatientID = '$patient_id'
                                          AND Appointment.DoctorID = '$doctor_id'
                                          AND Appointment.status = 'Done'";
                    
                    $medications_result = mysqli_query($connection, $medications_query);
                    
                    $medications = [];
                    while ($med_row = mysqli_fetch_assoc($medications_result)) {
                        $medications[] = htmlspecialchars($med_row['MedicationName']);
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['firstName'] . " " . $row['lastName']) . "</td>";
                    echo "<td>" . htmlspecialchars($age) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                    echo "<td>" . (!empty($medications) ? implode(", ", $medications) : "No medications prescribed") . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No past patients found.</td></tr>";
            }

            mysqli_close($connection);
            ?>
            <tbody>
        </table>
         
  
  
   
   <script>
   document.addEventListener("DOMContentLoaded", function() {
    const appointmentsTable = document.querySelectorAll("table")[0].querySelector("tbody"); 
    const rows = Array.from(appointmentsTable.rows);

    rows.sort((a, b) => {
        const dateA = parseDateTime(a.cells[0].innerText, a.cells[1].innerText);
        const dateB = parseDateTime(b.cells[0].innerText, b.cells[1].innerText);

        return dateA - dateB;
    });

    rows.forEach(row => appointmentsTable.appendChild(row)); 

    function parseDateTime(dateStr, timeStr) {
        const dateParts = dateStr.split('/');
        const day = parseInt(dateParts[0], 10);
        const month = parseInt(dateParts[1], 10);
        const year = parseInt(dateParts[2], 10);

        const tP = timeStr.trim().split(' ');
        const hourMin = tP[0].split(':');

        let hours = parseInt(hourMin[0], 10);
        let minutes = hourMin.length > 1 ? parseInt(hourMin[1], 10) : 0;
        const modifier = tP[1];

        if (modifier === "PM" && hours < 12) hours += 12;
        if (modifier === "AM" && hours === 12) hours = 0;

        return new Date(year, month - 1, day, hours, minutes);
    }
});
document.addEventListener("DOMContentLoaded", function () {
    let table = document.querySelectorAll("table")[1].querySelector("tbody"); 
    let rows = Array.from(table.rows);

    rows.sort((rowA, rowB) => {
        let nameA = rowA.cells[0].innerText.toLowerCase();
        let nameB = rowB.cells[0].innerText.toLowerCase();
        return nameA.localeCompare(nameB);
    });

    rows.forEach(row => table.appendChild(row));
});


</script>

        
        
        
    </body>
</html>
