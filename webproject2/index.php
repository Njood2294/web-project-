<?php
session_start();

// Database settings
$servername = "sql101.infinityfree.com";
$dbUsername = "if0_38801004";
$dbPassword = "100200300Ll";
$dbName = "if0_38801004_it329project";

$connection = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
// Connect to the database
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Message variables
$error_message = "";
$success_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Common fields
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check user role
    if (isset($_POST['speciality'])) {
        // Doctor data
        $specialityName = $_POST['speciality'];
        $role = 'doctor';

        // Get Speciality ID
        $specStmt = $conn->prepare("SELECT id FROM speciality WHERE speciality = ?");
        $specStmt->bind_param("s", $specialityName);
        $specStmt->execute();
        $specResult = $specStmt->get_result();
        $specRow = $specResult->fetch_assoc();
        $specialityID = $specRow['id'];

        // Check if email already exists
        $check = $connection->prepare("SELECT * FROM doctor WHERE emailAddress = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
        echo "<script>alert('This email is already registered! You will be redirected to the sign up page.'); window.location.href = 'index.php';</script>";
        } else {
            // Handle image upload
            $target_dir = __DIR__ . "/uploads/";
            $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $uniqueFileName = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $uniqueFileName;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

            // Insert doctor data
            $stmt = $connection->prepare("INSERT INTO doctor (id, firstName, lastName, uniqueFileName, SpecialityID, emailAddress, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssiss", $id, $firstName, $lastName, $uniqueFileName, $specialityID, $email, $password);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_type'] = 'doctor';
                header("Location: DoctorHomePage.php");
                exit();
            } else {
                $error_message = "Error registering doctor: " . $stmt->error;
            }
        }
    } else {
        // Patient data
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $role = 'patient';

        // Check if email already exists
        $check = $connection->prepare("SELECT * FROM patient WHERE emailAddress = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
        echo "<script>alert('This email is already registered! You will be redirected to the sign up page.'); window.location.href = 'index.php';</script>";
        } else {
            // Insert patient data
            $stmt = $connection->prepare("INSERT INTO patient (id, firstName, lastName, Gender, DoB, emailAddress, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $id, $firstName, $lastName, $gender, $dob, $email, $password);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_type'] = 'patient';
                header("Location: PatientHomePage.php");
                exit();
            } else {
                $error_message = "Error registering patient: " . $stmt->error;
            }
        }
    }
}

$connection->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
       body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; 
    background-color: #Edf1f6;
}
.left-section2 h1 {
    font-size: 2.25em; 
    margin: 0;
    color: #081f5c;
}

.left-section2 p {
    margin: 0.625em 0; 
    font-size: 1em; 
    color: #081f5c;
}

h2 {
    color: #081f5c;
}
.loaded {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%; 
    height: 100%; 
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.loaded-content {
    background-color: white;
    padding: 1.25em;
    border-radius: 0.625em; 
    text-align: center;
}

.loaded-content button {
    margin: 0.625em; 
    padding: 0.625em 1.25em; 
    font-size: 1em; 
    border: none;
    border-radius: 0.3125em; 
    cursor: pointer;
}

.loaded-content button:hover {
    background-color: #ddd;
}

.container2 {
    display: flex;
    width: 85%; 
    height: 80%; 
    box-shadow: 0px 0.25em 0.625em rgba(211, 210, 210, 0.1); 
    border-radius: 0.625em; 
    overflow: hidden;
}

.left-section2 {
    flex: 1;
    background-color: #7096D1;
    color: #000;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 2.5em;
}

.left-section2 h1 {
    font-size: 1.5em; 
    margin: 0;
}

.left-section2 p {
    margin: 0.625em 0; 
    font-size: 1em; 
}

.right-section2 {
    flex: 1.2;
    background-color: #fff;
    padding: 2.5em; 
    display: flex;
    flex-direction: column;
}

form {
    display: block; 
    opacity: 1;
    transition: opacity 0.3s ease-in-out;
    gap: 1.25em; 
}

label {
    font-weight: bold;
    margin-bottom: 0.3125em; 
}

input, .gender-options, select {
    padding: 0.625em; 
    border: 0.0625em solid #ddd; 
    border-radius: 0.3125em; 
    width: 100%;
    max-width: 25em; 
    box-sizing: border-box;
    margin-bottom: 0.9375em; 
}

.gender-options {
    display: flex;
    align-items: center;
    gap: 1.25em;
}

.gender-options label {
    display: flex;
    align-items: center;
    gap: 0.3125em;
}

input[type="radio"] {
    margin: 0;
    width: auto;
}

.submit-button {
    display: inline-block;
    padding: 0.9375em 1.875em; 
    background-color: #334eac;
    color: white;
    text-decoration: none;
    font-size: 1.125em; 
    border-radius: 0.5em; 
    margin-bottom: 0.9375em; 
}

.submit-button:hover {
    background-color: rgba(187, 210, 248, 0.842);
}

ul {
    list-style-type: none;
}     </style>
</head>
<body>
    <div id="load" class="loaded">
        <div class="loaded-content">
            <h2>Are you a Patient or a Doctor?</h2>
            <button onclick="select('patient')">Patient</button>
            <button onclick="select('doctor')">Doctor</button>
        </div>
    </div>

    <div class="container2" style="display: none;" id="forms-container">
        <div class="left-section2">
            <h1>Let's get you set up</h1>
            <p>It should only take a couple of minutes to sign up</p>
        </div>
        <div class="right-section2">
            
            <!-- Patient Form -->
            <form id="patient-form" style="display: none;" method="POST" action="index.php">
                <input type="hidden" name="role" value="patient">
                <ul>
                    <li><label for="first-name">First Name</label></li>
                    <input type="text" name="first-name" placeholder="Enter your first name" required>

                    <li><label for="last-name">Last Name</label></li>
                    <input type="text" name="last-name" placeholder="Enter your last name" required>

                    <li><label for="id">ID</label></li>
                    <input type="text" name="id" placeholder="Enter your ID" required>

                    <li><label for="dob">Date of Birth</label></li>
                    <input type="date" name="dob" required>

                    <li><label for="email">Email</label></li>
                    <input type="email" name="email" placeholder="Enter your email" required>

                    <li><label for="password">Password</label></li>
                    <input type="password" name="password" placeholder="Enter your password" required>

                    <li><label>Gender</label></li>
                    <div class="gender-options">
                        <input type="radio" name="gender" value="Male" required>
                        <label for="male">Male</label>
                        <input type="radio" name="gender" value="Female" required>
                        <label for="female">Female</label>
                    </div>
                </ul>
                <button type="submit" class="submit-button">Submit</button>
            </form>

            <!-- Doctor Form -->
            <form id="doctor-form" style="display: none;" method="POST" enctype="multipart/form-data" action="index.php">
                <input type="hidden" name="role" value="doctor">
                <ul>
                    <li><label for="first-name-doctor">First Name</label></li>
                    <input type="text" name="first-name" placeholder="Enter your first name" required>

                    <li><label for="last-name-doctor">Last Name</label></li>
                    <input type="text" name="last-name" placeholder="Enter your last name" required>

                    <li><label for="id-doctor">ID</label></li>
                    <input type="text" name="id" placeholder="Enter your ID" required>
                    
                    <li><label for="image">Upload Image</label></li>
                    <input type="file" id="image" name="image" required>

                    <li><label for="speciality">Speciality</label></li>
                    <select name="speciality" required>
                        <option value="" disabled selected>Select your speciality</option>
                        <option value="Dentist">Dentist</option>
                        <option value="Family medicine">Family medicine</option>
                        <option value="Dermatology">Dermatology</option>
                    </select>

                    <li><label for="email-doctor">Email</label></li>
                    <input type="email" name="email" placeholder="Enter your email" required>

                    <li><label for="password-doctor">Password</label></li>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </ul>
                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function select(role) {
            document.getElementById('load').style.display = 'none';
            document.getElementById('forms-container').style.display = 'flex';

            if (role === 'patient') {
                document.getElementById('patient-form').style.display = 'block';
                document.getElementById('doctor-form').style.display = 'none';
            } else if (role === 'doctor') {
                document.getElementById('patient-form').style.display = 'none';
                document.getElementById('doctor-form').style.display = 'block';
            }
        }
    </script>
</body>
</html>
