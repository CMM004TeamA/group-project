<?php
require_once "connect.php";

//check to see if the submit button was clicked
if (isset($_POST["submit"])) {
    //obtain the email(santize to ensure safety) and password from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];


$stmt = $conn ->prepare("SELECT email,username,password_hash FROM users WHERE email = ?");
$stmt ->bind_param("s", $email);
$stmt ->execute();
$result = $stmt->get_result();


if ($result->num_rows>0) {

    $row = $result->fetch_assoc();

    if (password_verify($password, $row["password_hash"])) {

        echo "<script type='text/javascript'>alert('Login Successful!');</script>";
        echo "Username:".$row["username"]."<br>";
        echo "Email:".$row["email"]."<br>";
        echo "Password:".$row["password_hash"]."<br>";
    }
    else {
        echo "Incorrect email or password.";
    }
}
else {
    echo "Invalid email or password.";
}


$stmt -> close();
$conn -> close();
}
?>