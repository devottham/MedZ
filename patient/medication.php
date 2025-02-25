<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Medication Reminders</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .details-container {
            display: flex; /* Use flexbox to arrange children side by side */
            margin-top: 30px;
        }
        .details-table {
            width: 100%;
            margin-left: 20px; /* Add some spacing between the heading and the table */
            font-size: 18px;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .details-table th {
            text-align: left;
            padding: 8px;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    // Check session validity
    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }

    // Set timezone and today's date
    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');
    ?>

    <!-- Container for the whole layout -->
    <div class="container">
        <!-- Navigation Menu -->
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td colspan="2" style="padding:10px">
                        <table class="profile-container" border="0">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/pat.png" alt="User" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($_SESSION["user"], 0, 13); ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($_SESSION["user"], 0, 22); ?></p>
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
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-sess menu-active menu-icon-sess-active">
                        <a href="medication.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Patient Record</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-AI">
                        <a href="ai.php" class="non-style-link-menu"><div><p class="menu-text">Chat Bot</p></a></div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Main content area to display user details -->
        <div class="details-container">
            <h3 style='font-size:24px; margin-top:20px; margin-left:30px;'>User Details:</h3>
            <?php
            if (isset($_SESSION["user"])) {
                $user_email = $_SESSION["user"];

                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "edoc";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT patient_id, patient_name, patient_age, patient_gender, patient_address, patient_phone, pemail, medical_history, medication_records, recent_visits, file_path FROM patient_records WHERE pemail = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $user_email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<table class='details-table' style='margin-top: 50px;'>
        <tr><th>Field</th><th>Information</th></tr>";


                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>Patient ID:</td><td>" . $row["patient_id"] . "</td></tr>";
                        echo "<tr><td>Patient Name:</td><td>" . $row["patient_name"] . "</td></tr>";
                        echo "<tr><td>Patient Age:</td><td>" . $row["patient_age"] . "</td></tr>";
                        echo "<tr><td>Patient Gender:</td><td>" . $row["patient_gender"] . "</td></tr>";
                        echo "<tr><td>Patient Address:</td><td>" . $row["patient_address"] . "</td></tr>";
                        echo "<tr><td>Patient Phone:</td><td>" . $row["patient_phone"] . "</td></tr>";
                        echo "<tr><td>Email:</td><td>" . $row["pemail"] . "</td></tr>";
                        echo "<tr><td>Medical History:</td><td>" . $row["medical_history"] . "</td></tr>";
                        echo "<tr><td>Medication Records:</td><td>" . $row["medication_records"] . "</td></tr>";
                        echo "<tr><td>Recent Visits:</td><td>" . $row["recent_visits"] . "</td></tr>";
                        echo "<tr><td>File Path:</td><td><img src='" . $row["file_path"] . "' alt='Patient Photo' style='width:100px; height:auto;'></td></tr>";

                    }
                    echo "</table>";
                } else {
                    echo "No records found for this user.";
                }

                $stmt->close();
                $conn->close();
            } else {
                echo "User not logged in.";
            }
            ?>
        </div>
    </div>
</body>
</html>
