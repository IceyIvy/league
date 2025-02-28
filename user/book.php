<?php

    // Include the database connection
    require_once '../db_connection.php';

    // Fetch booked slots for the selected date
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['date'])) {
        $date = $_GET['date'];
        $query = "SELECT time FROM bpslo_bookings WHERE date = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookedTimes = [];
        while ($row = $result->fetch_assoc()) {
            $bookedTimes = array_merge($bookedTimes, explode(',', $row['time']));
        }

        echo json_encode($bookedTimes);
        exit;
    }

    // Handle the booking submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $booking_id = uniqid('BOOKID');
        $date = $_POST['date'];
        $times = $_POST['times'];
        $name = $_POST['name'];
        $mobile_number = $_POST['mobile'];
        $email = $_POST['email'];
        $gcash_reference = $_POST['reference'];
        $payment = $_POST['totalCost'];

        $query = "INSERT INTO bpslo_bookings (
                    booking_id,
                    date,
                    time,
                    name,
                    mobile_number,
                    email,
                    gcash_reference,
                    payment)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssssssss",
                    $booking_id,
                    $date,
                    $times,
                    $name,
                    $mobile_number,
                    $email,
                    $gcash_reference,
                    $payment);

        if ($stmt->execute()) {
            // echo "Booking successful! Your booking ID is: " . $booking_id;
            ?>
                <div class="pop-up">
                <h1>Booking Successful!</h1>
                <br>
                <h3><?php echo "Your booking ID is " . $booking_id;  ?></h3>

                <a href="../index.php"><button type="submit" id="home-btn" class="submit-btn">Home</button></a>
            </div>

            <style>
                .pop-up{
                position: absolute;
                height: 350px;
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
        $mysqli->close();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/book.css">
    <style>
        .time-button:disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            border: 1px solid #999;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="form-section">

            <h2>Select a Date and Time</h2>

            <form id="bookingForm" method="POST">

                <div class="input-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" min="2025-01-01" max="2025-12-31" required>
                </div>

                <div class="input-group">
                    <label>Time:</label>
                    <div class="time-options" id="timeOptions">
                        <button type="button" class="time-button" data-time="07:00 AM">07:00 AM</button>
                        <button type="button" class="time-button" data-time="08:00 AM">08:00 AM</button>
                        <button type="button" class="time-button" data-time="09:00 AM">09:00 AM</button>
                        <button type="button" class="time-button" data-time="10:00 AM">10:00 AM</button>
                        <button type="button" class="time-button" data-time="11:00 AM">11:00 AM</button>
                        <button type="button" class="time-button" data-time="12:00 PM">12:00 PM</button>
                        <button type="button" class="time-button" data-time="01:00 PM">01:00 PM</button>
                        <button type="button" class="time-button" data-time="02:00 PM">02:00 PM</button>
                        <button type="button" class="time-button" data-time="03:00 PM">03:00 PM</button>
                        <button type="button" class="time-button" data-time="04:00 PM">04:00 PM</button>
                        <button type="button" class="time-button" data-time="05:00 PM">05:00 PM</button>
                        <button type="button" class="time-button" data-time="06:00 PM">06:00 PM</button>
                        <button type="button" class="time-button" data-time="07:00 PM">07:00 PM</button>
                        <button type="button" class="time-button" data-time="08:00 PM">08:00 PM</button>
                    </div>
                </div>

                <h2>Payment</h2>
                <div class="payment-section">
                    <p>Please scan this GCash QR Code to pay<br>GCash Number: 0912-345-6789</p>
                    <img src="../images/qr_code.png" alt="GCash QR Code" class="qr-code">
                </div>

                <h2>Client Details</h2>
                <div class="client-details">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Last Name, First Name M.I." required>

                    <div class="contact-info">
                        <div class="contact-field">
                            <label for="mobile">Mobile Number:</label>
                            <input type="text" id="mobile" name="mobile" placeholder="0000-000-0000" required>
                        </div>

                        <div class="contact-field">
                            <label for="email">Email Address:</label>
                            <input type="email" id="email" name="email" placeholder="@gmail.com" required>
                        </div>
                    </div>

                    <label for="reference">GCash Reference Number:</label>
                    <input type="text" id="reference" name="reference" placeholder="Enter Reference Number" required>

                </div>
                    <input type="hidden" name="times" id="times">
                    <input type="hidden" name="totalCost" id="totalCost">
            </form>
        </div>

        <div class="details-wrapper">

            <div class="details-section">
                <h2>Booking Details</h2>
                <div class="details-box">
                    <p id="bookingDate">Select a date and time</p>
                </div>
            </div>

            <div class="details-section">
                <h2>Payment Details</h2>
                <div class="details-box">
                    <p>Total <span id="totalCostDisplay">₱0.00</span></p>
                </div>
                <button class="book-button" id="bookNowButton" onclick="submitBooking()">Book Now</button>
            </div>

            <div class="details-section">
                <div class="details-box">
                    <button class="book-button" id="bookNowButton" onclick="window.location.href='./view_bookings_approved.php'">View Approved Bookings</button>
                </div>
            </div>

            <style>
                a{
                    text-decoration: none;
                    color: #66AD00;
                }
            </style>

        </div>
    </div>

    <script>
        let selectedTimes = [];
        let totalCost = 0;

        async function fetchBookedTimes(date) {
            selectedTimes = [];
            totalCost = 0;
            document.getElementById('bookingDate').innerText = "Select a date and time";
            document.getElementById('totalCostDisplay').innerText = "₱0.00";

            const timeButtons = document.querySelectorAll('.time-button');
            timeButtons.forEach(button => button.classList.remove('selected'));

            const response = await fetch(`?date=${date}`);
            const bookedTimes = await response.json();

            timeButtons.forEach(button => {
                if (bookedTimes.includes(button.dataset.time)) {
                    button.disabled = true;
                } else {
                    button.disabled = false;
                }
            });
        }

        function updateBookingDetails() {
            const bookingDate = document.getElementById('date').value || 'No date selected';
            document.getElementById('bookingDate').innerText = "Date: " + bookingDate + ", Times: " + selectedTimes.join(" | ");
            document.getElementById('totalCostDisplay').innerText = '₱' + totalCost.toFixed(2);
        }

        function selectTime(time, button) {
            const index = selectedTimes.indexOf(time);
            if (index === -1) {
                selectedTimes.push(time);
                totalCost += 200;
                button.classList.add('selected');
            } else {
                selectedTimes.splice(index, 1);
                totalCost -= 200;
                button.classList.remove('selected');
            }

            updateBookingDetails();
        }

        document.querySelectorAll('.time-button').forEach(button => {
            button.addEventListener('click', function() {
                selectTime(this.dataset.time, this);
            });
        });

        document.getElementById('date').addEventListener('change', function() {
            fetchBookedTimes(this.value);
        });

        function submitBooking() {
            const bookingDetails = {
                date: document.getElementById('date').value,
                times: selectedTimes.join(','),
                name: document.getElementById('name').value,
                mobile: document.getElementById('mobile').value,
                email: document.getElementById('email').value,
                reference: document.getElementById('reference').value,
                totalCost: totalCost
            };

            if (!bookingDetails.date || selectedTimes.length === 0 || !bookingDetails.name || !bookingDetails.mobile || !bookingDetails.email || !bookingDetails.reference) {
                alert('Please complete all required fields and select at least one time slot.');
                return;
            }

            document.getElementById('times').value = bookingDetails.times;
            document.getElementById('totalCost').value = bookingDetails.totalCost;
            document.getElementById('bookingForm').submit();
        }
    </script>
</body>

</html>
