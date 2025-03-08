<?php
require_once "connect.php";

//check to see if a form was submitted
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    //obtain the username and password from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    //create a sql command to retreive the data
$sql = "SELECT email,username,password_hash FROM users WHERE email = ?";

$stmt = mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

//check if any data was obtained
if (mysqli_num_rows($result) > 0) {

    //a username matches
    $row = mysqli_fetch_assoc($result);

    //check if password is also correct
    if (password_verify($password, $row["password_hash"])) {

        //display information
        echo "Username:".$row["username"]."<br>";
        echo "Email:".$row["email"]."<br>";
        echo "Password:".$row["password_hash"]."<br>";
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

//close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
}
?>