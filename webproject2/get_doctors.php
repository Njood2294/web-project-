<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

$connect = mysqli_connect("sql101.infinityfree.com", "if0_38801004", "100200300Ll", "if0_38801004_it329project");


$doctors = [];

if (isset($_GET['speciality_id']) && $_GET['speciality_id'] != '') {
    $id = $_GET['speciality_id'];
    $sql = "SELECT Doctor.id, Doctor.firstName, Doctor.lastName, Speciality.speciality 
            FROM Doctor 
            JOIN Speciality ON Doctor.SpecialityID = Speciality.id 
            WHERE SpecialityID = '$id'";
} else {
    $sql = "SELECT Doctor.id, Doctor.firstName, Doctor.lastName, Speciality.speciality 
            FROM Doctor 
            JOIN Speciality ON Doctor.SpecialityID = Speciality.id";
}

$result = mysqli_query($connect, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $doctors[] = $row;
}

echo json_encode($doctors);
?>
