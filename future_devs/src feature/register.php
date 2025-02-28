<?php

  // Include database connection
  require_once '../db_connection.php';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Capture form data
      $sport = $_POST['sport'] ?? '';
      $team_name = $_POST['team-name'] ?? '';
      $division = $_POST['division'] ?? '';  // Default to an empty string if not set
      $first_name = $_POST['first-name'] ?? '';
      $middle_name = $_POST['middle-name'] ?? '';
      $last_name = $_POST['last-name'] ?? '';
      $birth_date = $_POST['birth-date'] ?? '';
      $age = $_POST['age'] ?? '';
      $sex = $_POST['sex'] ?? '';
      $sitio = $_POST['sitio'] ?? '';
      $mobile_number = $_POST['mobile-number'] ?? '';
      $email_address = $_POST['email-address'] ?? '';

      // Set division automatically if sport is Basketball
      if ($sport == "Basketball" && empty($division)) {
          $division = "Men's";
      }

      // Handle image uploads
      $photo = isset($_FILES['photo']) ? file_get_contents($_FILES['photo']['tmp_name']) : null;
      $nso = isset($_FILES['nso']) ? file_get_contents($_FILES['nso']['tmp_name']) : null;
      $voter_cert = isset($_FILES['voter-cert']) ? file_get_contents($_FILES['voter-cert']['tmp_name']) : null;

      // Generate unique application ID
      $application_id = uniqid('REGID');  // Create a unique application ID

      // Check if division is empty (this happens if it's not set by the user)
      if (empty($division)) {
          echo "Division is required.";
          exit;
      }

      // Insert data into the database
      $stmt = $mysqli->prepare("INSERT INTO bpslo_registrations (
                                      application_id,
                                      sport,
                                      team_name,
                                      division,
                                      first_name,
                                      middle_name,
                                      last_name,
                                      birth_date,
                                      age,
                                      sex,
                                      sitio,
                                      mobile_number,
                                      email_address,
                                      photo,
                                      nso,
                                      voter_cert)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

      $stmt->bind_param("ssssssssssssssss",
                        $application_id,
                        $sport,
                        $team_name,
                        $division,
                        $first_name,
                        $middle_name,
                        $last_name,
                        $birth_date,
                        $age,
                        $sex,
                        $sitio,
                        $mobile_number,
                        $email_address,
                        $photo,
                        $nso,
                        $voter_cert);

      if ($stmt->execute()) {
        ?>
          <div class="pop-up">
              <h1>Registered Successfully</h1>
              <a href="../index.php"><button type="submit" id="home-btn" class="submit-btn">Home</button></a>
          </div>

          <style>
            .pop-up{
              position: absolute;
              height: 300px;
              width: 600px;
              background-color: white;
              border-radius: 10px;
              border: 1px solid var(--green);
              padding: 5px;
              text-align: center;
              box-shadow: 0 0 3px black;
            }

            h1{
              color: var(--green);
              font-size: 50px;
            }

            #home-btn{
              width: 200px;
            }
          </style>

          <?php
      } else {
          echo "Error: " . $stmt->error;
      }

      $stmt->close();
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="../styles/register.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <form class="registration-form" id="registrationForm" action="" method="POST" enctype="multipart/form-data">
      <h1>Registration Form</h1>
      <p>Fill out the form carefully for registration</p>

      <!-- Sport, Team Name, Division -->
      <div class="form-row">
        <div class="form-group">
          <label for="sport">Sport:</label>
          <select id="sport" name="sport" onchange="updateDivision()" required>
            <option value="" disabled selected>Please Select</option>
            <option value="Basketball">Basketball</option>
            <option value="Volleyball">Volleyball</option>
          </select>
        </div>

        <div class="form-group">
          <label for="team-name">Team Name:</label>
          <input type="text" id="team-name" name="team-name" placeholder="Team Name" required>
        </div>

        <div class="form-group">
          <label for="division">Division:</label>
          <select id="division" name="division" required>
            <option value="" disabled selected>Please Select</option>
            <option value="Women's">Women's</option>
            <option value="Men's">Men's</option>
          </select>
        </div>
      </div>

      <!-- Player Name -->
      <div class="form-row">
        <div class="form-group full-width">
            <label>Player Name:</label>
            <div class="name-fields">
                <input type="text" id="first-name" name="first-name" placeholder="First Name" required>
                <input type="text" id="middle-name" name="middle-name" placeholder="Middle Name" required>
                <input type="text" id="last-name" name="last-name" placeholder="Last Name" required>
            </div>
        </div>
      </div>

      <!-- Birth Date, Age, Sex -->
      <div class="form-row">
        <div class="form-group">
          <label for="birth-date">Birth Date:</label>
          <input type="date" id="birth-date" name="birth-date" onchange="calculateAge()" required>
        </div>

        <div class="form-group">
          <label for="age">Age:</label>
          <input type="text" id="age" name="age" readonly>
        </div>

        <div class="form-group">
          <label for="sex">Sex:</label>
          <select id="sex" name="sex" required>
            <option value="" disabled selected>Please Select</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
      </div>

      <!-- Sitio, Mobile Number, Email Address -->
      <div class="form-row">
        <div class="form-group">
          <label for="sitio">Sitio:</label>
          <select id="sitio" name="sitio" required>
            <option value="" disabled selected>Please Select</option>
            <option>L. Flores</option>
            <option>Mahayahay 1</option>
            <option>Mahayahay 2</option>
            <option>L. Sun-ok</option>
            <option>L. Puthawan</option>
            <option>Magsaysay</option>
            <option>C. Groove Street</option>
            <option>C. Riverside</option>
            <option>C. Stallion</option>
            <option>T. Cavan</option>
            <option>Rallos</option>
            <option>Truman</option>
          </select>
        </div>

        <div class="form-group">
          <label for="mobile-number">Mobile Number:</label>
          <input type="text" id="mobile-number" name="mobile-number" placeholder="0000-000-0000" required>
        </div>

        <div class="form-group">
            <label for="email-address">Email Address:</label>
            <input type="text" id="email-address" name="email-address" placeholder="@gmail.com" required>
        </div>
      </div>

      <!-- Requirements -->
      <div class="form-row requirements">
        <h2>Requirements:</h2>
        <div class="file-group">
          <label for="photo">2x2 Picture with Name:</label>
          <input type="file" id="photo" name="photo" accept="image/*" required>
        </div>

        <div class="file-group">
          <label for="nso">NSO:</label>
          <input type="file" id="nso" name="nso" accept="image/*" required>
        </div>

        <div class="file-group">
          <label for="voter-cert">Voter's Certificate:</label>
          <input type="file" id="voter-cert" name="voter-cert" accept="image/*" required>
        </div>
      </div>

      <!-- Button -->
      <button type="submit" class="submit-btn">Submit</button>
    </form>
  </div>

  <script>
    function updateDivision() {
      const sport = document.getElementById('sport').value;
      const division = document.getElementById('division');

      // if (sport === 'Basketball') {
      //   division.value = "Men's";
      //   division.disabled = true;
      // } else {
      //   division.disabled = false;
      //   division.value = '';
      // }
    }

    function calculateAge() {
      const birthDateInput = document.getElementById("birth-date").value;

      if (birthDateInput) {
        const [year, month, day] = birthDateInput.split('-');
        const birthDateObj = new Date(year, month - 1, day);
        const today = new Date();

        let age = today.getFullYear() - birthDateObj.getFullYear();
        const monthDiff = today.getMonth() - birthDateObj.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
          age--;
        }

        document.getElementById("age").value = age;
      } else {
        document.getElementById("age").value = '';
      }
    }
  </script>


</body>
</html>
