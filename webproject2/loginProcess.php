<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php?error=invalidCredentials");
    exit();
}

$email    = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role     = $_POST['role']     ?? '';

if (empty($email) || empty($password) || empty($role)) {
    header("Location: login.php?error=missingFields");
    exit();
}

$servername = "localhost";
$dbUsername = "root";  
$dbPassword = "root";  
$dbName     = "it329project";

$connection = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($role === 'doctor') {
    $sql = "SELECT id, emailAddress, password
            FROM doctor
            WHERE emailAddress='$email'
            LIMIT 1";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Use password_verify to compare the plaintext password with the hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['user_type'] = 'doctor';
            header("Location: DoctorHomePage.php");
            exit();
        } else {
            header("Location: login.php?error=invalidCredentials");
            exit();
        }
    } else {
        $checkPatient = "SELECT id FROM patient WHERE emailAddress='$email' LIMIT 1";
        $patientRes = mysqli_query($conn, $checkPatient);
        if ($patientRes && mysqli_num_rows($patientRes) > 0) {
            header("Location: login.php?error=wrongRole");
            exit();
        } else {
            header("Location: login.php?error=invalidCredentials");
            exit();
        }
    }

} elseif ($role === 'patient') {
    $sql = "SELECT id, emailAddress, password
            FROM patient
            WHERE emailAddress='$email'
            LIMIT 1";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['user_type'] = 'patient';
            header("Location: PatientHomePage.php");
            exit();
        } else {
            header("Location: login.php?error=invalidCredentials");
            exit();
        }
    } else {
        $checkDoctor = "SELECT id FROM doctor WHERE emailAddress='$email' LIMIT 1";
        $doctorRes = mysqli_query($connection, $checkDoctor);
        if ($doctorRes && mysqli_num_rows($doctorRes) > 0) {
            header("Location: login.php?error=wrongRole");
            exit();
        } else {
            header("Location: login.php?error=invalidCredentials");
            exit();
        }
    }

} else {
    header("Location: login.php?error=invalidRole");
    exit();
}
?>
