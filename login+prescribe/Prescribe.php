<?php
        include "session.php";


if (!isset($_GET['patient_id']) || !isset($_GET['appointment_id'])) {
    die("Missing patient_id or appointment_id in the query string.");
}

$patient_id     = $_GET['patient_id'];
$appointment_id = $_GET['appointment_id'];

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "root";
$dbName     = "it329project";

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 3. Fetch the patient's info from `patient` table
$sqlPatient = "SELECT * FROM patient WHERE id='$patient_id' LIMIT 1";
$resultPatient = mysqli_query($conn, $sqlPatient);

if ($resultPatient && mysqli_num_rows($resultPatient) > 0) {
    $patientRow = mysqli_fetch_assoc($resultPatient);
    $firstName = $patientRow['firstName'];
    $lastName  = $patientRow['lastName'];
    $gender    = $patientRow['Gender'];
    $dob       = $patientRow['DoB'];

    // Calculate approximate age from DoB (optional)
    $age = 0;
    if (!empty($dob)) {
        $birthDate = new DateTime($dob);
        $today     = new DateTime();
        $diff      = $today->diff($birthDate);
        $age       = $diff->y; // in years
    }
} else {
    // If no matching patient
    die("Patient not found in the database.");
}

// 4. Fetch all medications from `medication` table
$sqlMeds = "SELECT id, MedicationName FROM medication";
$resultMeds = mysqli_query($conn, $sqlMeds);
$medicationsList = [];
if ($resultMeds && mysqli_num_rows($resultMeds) > 0) {
    while ($row = mysqli_fetch_assoc($resultMeds)) {
        $medicationsList[] = $row; // each row: ['id', 'MedicationName']
    }
}

// Close the DB connection if you want (not strictly necessary)
 mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescribe Medication</title>
    <link rel="stylesheet" href="Prescribe.css"> <!-- your CSS file -->
</head>
<body>
    <div class="split-layout">
        <div class="left-section">
            <h1>Prescribe with Care</h1>
            <p>Provide the best medications tailored to your patient’s needs. Ensure accuracy and care in every prescription.</p>
            <img src="meds.png" alt="Meds">
        </div>

        <div class="right-section">
            <div class="form-container">
                <h1 class="form-title">Patient's Medications</h1>
                
                <!-- 5. The form posts to `processPrescription.php` -->
                <form action="processPrescription.php" method="POST">
                    <!-- Hidden inputs to pass IDs to the next page -->
                    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
                    <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>">

                    <div class="input-group">
                        <label for="patient-name">Patient's Name</label>
                        <input type="text" id="patient-name"
                               value="<?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>"
                               readonly>
                    </div>

                    <div class="input-group">
                        <label for="age">Age</label>
                        <input type="number" id="age"
                               value="<?php echo htmlspecialchars($age); ?>"
                               readonly>
                    </div>

                    <div class="input-group">
                        <label>Gender</label>
                        <div class="gender-options">
                            <label class="gender-card">
                                <input type="radio" name="gender" value="Male"
                                    <?php if ($gender === 'Male') echo 'checked'; ?>>
                                <div class="gender-content">
                                    <i>👨</i>
                                    <span>Male</span>
                                </div>
                            </label>
                            <label class="gender-card">
                                <input type="radio" name="gender" value="Female"
                                    <?php if ($gender === 'Female') echo 'checked'; ?>>
                                <div class="gender-content">
                                    <i>👩</i>
                                    <span>Female</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Medications</label>
                        <div class="medications-list">
                            <!-- Dynamically create checkboxes for each medication in the DB -->
                            <?php foreach ($medicationsList as $med): ?>
                                <label class="custom-checkbox">
                                    <input type="checkbox" name="medications[]" value="<?php echo $med['id']; ?>">
                                    <span class="checkbox-content">
                                        <i>💊</i>
                                        <span><?php echo htmlspecialchars($med['MedicationName']); ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
