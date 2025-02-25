<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Add Patient Details</title>
    <style>
        .dashboard-tables, .doctor-header {
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table, #anim {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .doctor-header {
            animation: transitionIn-Y-over 0.5s;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'd'){
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }

    include("../connection.php");

    // SQL query to fetch doctor information
    $userrow = $database->query("SELECT * FROM doctor WHERE docemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $username = $userfetch["docname"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Fetch form inputs
        $patient_id = $_POST['patient_id'];
        $patient_name = $_POST['patient_name'];
        $patient_age = $_POST['patient_age'];
        $patient_gender = $_POST['patient_gender'];
        $patient_address = $_POST['patient_address'];
        $patient_phone = $_POST['patient_phone'];
        $pemail = $_POST['pemail'];
        $medical_history = $_POST['medical_history'];
        $medication_records = $_POST['medication_records'];
        $recent_visits = $_POST['recent_visits'];

        // File upload handling
        $file_path = null;
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES['file_upload']['tmp_name'];
            $file_name = $_FILES['file_upload']['name'];
            $upload_dir = '../uploads/';

            // Create upload directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move the file to the designated folder
            if (move_uploaded_file($file_tmp_path, $upload_dir . $file_name)) {
                $file_path = $upload_dir . basename($file_name);
            } else {
                echo "<script>alert('Failed to upload file.');</script>";
            }
        }

        // SQL query to insert or update patient details
        $query = "INSERT INTO patient_records (patient_id, patient_name, patient_age, patient_gender, patient_address, patient_phone, pemail, medical_history, medication_records, recent_visits, file_path) 
                  VALUES ('$patient_id', '$patient_name', '$patient_age', '$patient_gender', '$patient_address', '$patient_phone', '$pemail', '$medical_history', '$medication_records', '$recent_visits', '$file_path')
                  ON DUPLICATE KEY UPDATE 
                  patient_name='$patient_name', patient_age='$patient_age', patient_gender='$patient_gender', patient_address='$patient_address', patient_phone='$patient_phone', 
                  pemail='$pemail', medical_history='$medical_history', medication_records='$medication_records', recent_visits='$recent_visits', file_path='$file_path'";

        if ($database->query($query) === TRUE) {
            echo "<script>alert('Patient details updated successfully!');</script>";
        } else {
            echo "<script>alert('Failed to update patient details.');</script>";
        }
    }
    ?>

    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/doc.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13); ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-dashbord">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Appointments</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">My Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">My Patients</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor menu-active">
                        <a href="add.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Add Patient Details</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
            </table>
        </div>

        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;">
                <tr>
                    <td colspan="1" class="nav-bar">
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Add Patient Details</p>
                    </td>
                    <td width="25%"></td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Today's Date</p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                            date_default_timezone_set('Asia/Kolkata');
                            echo date('Y-m-d'); 
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <center>
                            <form action="add.php" method="POST" enctype="multipart/form-data" class="sub-table scrolldown" style="width: 80%;">
                                <table width="100%" class="filter-container" border="0">
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="patient_id" class="form-label">Patient ID</label>
                                            <input type="text" name="patient_id" class="input-text" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="patient_name" class="form-label">Patient Name</label>
                                            <input type="text" name="patient_name" class="input-text" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="patient_age" class="form-label">Patient Age</label>
                                            <input type="number" name="patient_age" class="input-text" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="patient_gender" class="form-label">Patient Gender</label>
                                            <input type="text" name="patient_gender" class="input-text" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="patient_address" class="form-label">Patient Address</label>
                                            <input type="text" name="patient_address" class="input-text" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="patient_phone" class="form-label">Patient Phone</label>
                                            <input type="text" name="patient_phone" class="input-text" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="pemail" class="form-label">Patient Email</label>
                                            <input type="text" name="pemail" class="input-text" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="medical_history" class="form-label">Medical History</label>
                                            <textarea id="medical_history" name="medical_history" class="input-text" rows="4" cols="50" required></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="medication_records" class="form-label">Medication Records</label>
                                            <textarea id="medication_records" name="medication_records" class="input-text" rows="4" cols="50" required></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="recent_visits" class="form-label">Recent Visits</label>
                                            <textarea id="recent_visits" name="recent_visits" class="input-text" rows="4" cols="50" required></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; padding: 25px;">
                                            <label for="file_upload" class="form-label">Upload File</label>
                                            <input type="file" name="file_upload" class="input-text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 25px 0;">
                                            <center><button type="submit" class="btn-primary btn" style="width: 50%;">Add Patient Details</button></center>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
