<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<?php
include "session.php";
$connect = mysqli_connect("sql101.infinityfree.com", "if0_38801004", "100200300Ll", "if0_38801004_it329project");

$patient_id = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["doctor"], $_POST["date"], $_POST["time"], $_POST["reason"])) {
    $n = $_POST["doctor"];
    $d = $_POST["date"];
    $t = $_POST["time"];
    $r = $_POST["reason"];

    $sql = "INSERT INTO appointment(PatientID, DoctorID, date, time, reason, status) 
            VALUES('$patient_id', '$n', '$d', '$t', '$r', 'Pending')";
    if (mysqli_query($connect, $sql)) {
        header("Location: PatientHomePage.php?message=Appointment booked successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($connect);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hope clinic</title>
    <link rel="stylesheet" href="Css-PatientHomePage.css">
</head>
<body>
<main id="Appointment"> 
    <h1>Book an Appointment</h1>

    <form id="form2" action="Appointment.php" method="POST">
        <label>Select Speciality: </label>
        <select name="speciality" id="speciality">
            <option value="">-- All Specialities --</option>
            <?php
            
            $sql = "SELECT * FROM Speciality";
            $result = mysqli_query($connect, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . $row['speciality'] . "</option>";
            }
            
              
            
            ?>
        </select><br>

        <label>Select Doctor: </label>
        <select id="doctor" name="doctor" required>
            <option value="">-- Select Doctor --</option>
             <?php  $sql="SELECT * FROM Doctor";
                $result=mysqli_query($connect,$sql);
                while($row=mysqli_fetch_assoc($result)){
                    $sql="SELECT speciality FROM Speciality WHERE id='".$row['SpecialityID']."'";
                    $result2=mysqli_query($connect,$sql);
                    $row2=mysqli_fetch_assoc($result2);

                    echo "<option value='" .$row['id']. "'>" .$row['firstName']." ".$row['lastName']."-".$row2['speciality']. "</option>";
                
                }?>
            
        </select><br>

        <label>Select Date:
            <input type="date" name="date" required>
        </label><br>

        <label>Select Time:
            <input type="time" name="time" required>
        </label><br>

        <label>Reason for Visit:<br>
            <textarea name="reason" required></textarea>
        </label><br>

        <input type="submit" id="submit2" value="Book Appointment">
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#speciality').on('change', function () {
    var specialityId = $(this).val();
    $.ajax({
        url: 'get_doctors.php',
        type: 'GET',
        data: { speciality_id: specialityId },
        dataType: 'json',
        success: function (data) {
            $('#doctor').empty();
            $('#doctor').append('<option value="">-- Select Doctor --</option>');
            $.each(data, function (index, doctor) {
                $('#doctor').append(
                    '<option value="' + doctor.id + '">' + doctor.firstName + ' ' + doctor.lastName + ' - ' + doctor.speciality + '</option>'
                );
            });
        }
    });
});
</script>
</body>
</html>
