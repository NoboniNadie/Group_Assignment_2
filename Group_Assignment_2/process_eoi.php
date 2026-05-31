<?php
require_once 'settings.php';

// Block direct URL access - redirect if not POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['jobRef'])) {
    header('Location: apply.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Expression of Interest Submission | Learnova">
    <meta name="keywords" content="Apply, EOI, Expression of Interest, Jobs, Learnova">
    <meta name="author" content="Nusrat Islam">
    <title>Application | Learnova</title>
    <link rel="stylesheet" href="styles/styling.css">
<!-- Internal CSS -->
<style>
    section, div {
        font-family: Calibri;
    }
</style>


</head>

<?php include 'header.inc'; ?>

<!--Body of HTML Page-->
<body>

<?php

// ---- SANITISE ALL INPUTS ----
function sanitise($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$jobRef      = sanitise($_POST['jobRef'] ?? '');
$firstName   = sanitise($_POST['firstName'] ?? '');
$lastName    = sanitise($_POST['lastName'] ?? '');
$dob         = sanitise($_POST['dob'] ?? '');
$gender      = sanitise($_POST['gender'] ?? '');
$street      = sanitise($_POST['street'] ?? '');
$suburb      = sanitise($_POST['suburb'] ?? '');
$state       = sanitise($_POST['state'] ?? '');
$postcode    = sanitise($_POST['postcode'] ?? '');
$email       = sanitise($_POST['email'] ?? '');
$phone       = sanitise($_POST['phone'] ?? '');
$otherSkills = sanitise($_POST['otherSkills'] ?? '');
$skills      = isset($_POST['skills']) ? $_POST['skills'] : [];

// ---- VALIDATION ----
$errors = [];

// Job reference: exactly 5 alphanumeric characters
if (empty($jobRef)) {
    $errors[] = "Job reference number is required.";
} elseif (!preg_match('/^[A-Za-z0-9]{5}$/', $jobRef)) {
    $errors[] = "Job reference must be exactly 5 alphanumeric characters.";
}

// First name: letters only, max 20
if (empty($firstName)) {
    $errors[] = "First name is required.";
} elseif (!preg_match('/^[A-Za-z]{1,20}$/', $firstName)) {
    $errors[] = "First name must be letters only, max 20 characters.";
}

// Last name: letters only, max 20
if (empty($lastName)) {
    $errors[] = "Last name is required.";
} elseif (!preg_match('/^[A-Za-z]{1,20}$/', $lastName)) {
    $errors[] = "Last name must be letters only, max 20 characters.";
}

// Date of birth: dd/mm/yyyy, age between 15 and 80
if (empty($dob)) {
    $errors[] = "Date of birth is required.";
} elseif (!preg_match('/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20)\d\d$/', $dob)) {
    $errors[] = "Date of birth must be in dd/mm/yyyy format.";
} else {
    $dobDate = DateTime::createFromFormat('d/m/Y', $dob);
    $today = new DateTime();
    $age = $today->diff($dobDate)->y;
    if ($age < 15 || $age > 80) {
        $errors[] = "Applicant age must be between 15 and 80 years.";
    }
}

// Gender
if (empty($gender)) {
    $errors[] = "Gender is required.";
}

// Street address
if (empty($street)) {
    $errors[] = "Street address is required.";
} elseif (strlen($street) > 40) {
    $errors[] = "Street address must be max 40 characters.";
}

// Suburb
if (empty($suburb)) {
    $errors[] = "Suburb/Town is required.";
} elseif (strlen($suburb) > 40) {
    $errors[] = "Suburb must be max 40 characters.";
}

// State
$validStates = ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'];
if (empty($state) || !in_array($state, $validStates)) {
    $errors[] = "Please select a valid state.";
}

// Postcode: exactly 4 digits
if (empty($postcode)) {
    $errors[] = "Postcode is required.";
} elseif (!preg_match('/^\d{4}$/', $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
}

// Email
if (empty($email)) {
    $errors[] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

// Phone: 8-12 digits/spaces/hyphens
if (empty($phone)) {
    $errors[] = "Phone number is required.";
} elseif (!preg_match('/^[0-9\s\-\(\)]{8,12}$/', $phone)) {
    $errors[] = "Please enter a valid phone number.";
}

// Skills: at least one required
if (empty($skills)) {
    $errors[] = "Please select at least one skill.";
}

// ---- SHOW ERRORS IF ANY ----
if (!empty($errors)): ?>
    <h2>Please fix the following errors:</h2>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li style="color:red;"><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="apply.php">Go back and fix errors</a>

<?php else: ?>

    <?php
    // ---- CONNECT TO DATABASE ----
    $db_conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$db_conn) {
        die("<p style='color:red;'>Database connection failed.</p>");
    }

    // ---- CREATE EOI TABLE IF NOT EXISTS ----
    $createTable = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        jobRef VARCHAR(5) NOT NULL,
        firstName VARCHAR(20) NOT NULL,
        lastName VARCHAR(20) NOT NULL,
        dob VARCHAR(10) NOT NULL,
        gender VARCHAR(10) NOT NULL,
        street VARCHAR(40) NOT NULL,
        suburb VARCHAR(40) NOT NULL,
        state VARCHAR(3) NOT NULL,
        postcode VARCHAR(4) NOT NULL,
        email VARCHAR(50) NOT NULL,
        phone VARCHAR(12) NOT NULL,
        skills VARCHAR(255),
        otherSkills TEXT,
        status ENUM('New', 'Current', 'Final') DEFAULT 'New'
    )";
    mysqli_query($db_conn, $createTable);

    // ---- ESCAPE INPUTS FOR SQL ----
    $skillsStr   = mysqli_real_escape_string($db_conn, implode(', ', $skills));
    $jobRef      = mysqli_real_escape_string($db_conn, $jobRef);
    $firstName   = mysqli_real_escape_string($db_conn, $firstName);
    $lastName    = mysqli_real_escape_string($db_conn, $lastName);
    $dob         = mysqli_real_escape_string($db_conn, $dob);
    $gender      = mysqli_real_escape_string($db_conn, $gender);
    $street      = mysqli_real_escape_string($db_conn, $street);
    $suburb      = mysqli_real_escape_string($db_conn, $suburb);
    $state       = mysqli_real_escape_string($db_conn, $state);
    $postcode    = mysqli_real_escape_string($db_conn, $postcode);
    $email       = mysqli_real_escape_string($db_conn, $email);
    $phone       = mysqli_real_escape_string($db_conn, $phone);
    $otherSkills = mysqli_real_escape_string($db_conn, $otherSkills);

    // ---- INSERT RECORD ----
    $query = "INSERT INTO eoi 
              (jobRef, firstName, lastName, dob, gender, street, suburb, state, postcode, email, phone, skills, otherSkills)
              VALUES 
              ('$jobRef','$firstName','$lastName','$dob','$gender','$street','$suburb','$state','$postcode','$email','$phone','$skillsStr','$otherSkills')";

    $result = mysqli_query($db_conn, $query);

    if ($result):
        $eoiNumber = mysqli_insert_id($db_conn);
    ?>

        <!-- Success confirmation -->
        <h2>Application Submitted Successfully!</h2>
        <p>Thank you <strong><?php echo $firstName . " " . $lastName; ?></strong>, your application has been received.</p>
        <p>Your EOI Number is: <strong><?php echo $eoiNumber; ?></strong></p>
        <p>Please keep this number for your records.</p>
        <br>
        <a href="index.php">Return to Home</a>

    <?php else: ?>
        <p style="color:red;">Error submitting application. Please try again.</p>
        <a href="apply.php">Go back</a>
    <?php endif; ?>

    <?php mysqli_close($db_conn); ?>

<?php endif; ?>

</body>

<hr>

<?php include 'footer.inc'; ?>

</html>