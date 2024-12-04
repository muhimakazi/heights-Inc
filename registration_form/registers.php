
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div>
        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "registration";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }



    // Insert form data into database
    $sql = "INSERT INTO users (fullname,email, phonenumber) values (?,?,?)";
            $stmtinsert=$db->prepare($sql);
            $result=$stmtinsert->execute([$fullname,$email,$phonenumber]);

    if ($conn->query($sql) === TRUE) {
        sendConfirmationEmail($fullname, $email);
        echo "Registration successful. A confirmation email has been sent to $email.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

function sendConfirmationEmail($fullname, $email) {
    $to = $email;
    $subject = "Event Registration Confirmation";
    $message = "
    <html>
    <head>
        <title>Event Registration Confirmation</title>
    </head>
    <body>
        <p>Dear $name,</p>
        <p>Thank you for registering for our event. We look forward to seeing you there!</p>
        <p>Best regards,<br>Event Organizer</p>
    </body>
    </html>
    ";

    // To send HTML mail, the Content-type header must be set
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Additional headers
    $headers .= 'From: cmuhimakazi@gmail.com' . "\r\n";

    mail($to, $subject, $message, $headers);
}
?>
    </div>
    <div class="container">
        <h2 class="text-center">Event Registration Form</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>
