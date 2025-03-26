<?php
require_once "../connect.php";
session_start();

//check to see if a form was submitted
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    //obtain the username and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    //create a sql command to retreive the data
$sql = "SELECT user_id,username,email,password_hash FROM users WHERE email = :email";

$stmt = $conn ->prepare($sql);
$stmt->execute(['email' => $email]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

//check if any data was obtained
if (($result) > 0) {

    //check if password is also correct
    if (password_verify($password, $result["password_hash"])) {

        //set session variables
        $_SESSION['user_name'] = $result['username'];
        $_SESSION['user_email'] = $result['email'];
        $_SESSION['user_id'] = $result['user_id'];

        //display information
        echo "<script type='text/javascript'>alert('Login Successful!');</script>";

        echo "<script type='text/javascript'>window.location.href = 'http://localhost/WEBSITES/DONATION%20WEBSITE/group-project-main/group-project-main/profile.php';</script>";
    }
    else {
        //password did not match
        echo "Incorrect Password.";
    }
}
else {
    //username did not match
    echo "Invalid username.";
}
}
?>
