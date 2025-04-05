<?php
require_once "connect.php";

//when i submit my form
if (isset($_POST["submit"])) {
    $user_name = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeat_password = $_POST["repeat_password"];
    $role = $_POST["role"];

    $password_hidden = password_hash($password, PASSWORD_DEFAULT);

    $error_message = array();

    if (empty($user_name) or empty($email) or empty($password) or empty($repeat_password)) {
        array_push($error_message, "All fields must be filled!");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($error_message, "Email is not valid.");
    }
    if (strlen($password) < 8) {
        array_push($error_message, "Password must not be less than 8 characters");
    }
    if ($password <> $repeat_password) {
        array_push($error_message, "Password is not identical");
    }

    if (count($error_message) > 0) {
        foreach ($error_message as $error) {
            echo $error;
        }
    } else {
        require_once("registration.php");
    }
}


//database check for email
$stmt = $conn ->prepare("SELECT email FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result){
echo "This user is already registered";
}
else {
    try{
        $sql = $conn->prepare("INSERT INTO users (username, email, password_hash, role) 
                               VALUES (:username, :email, :password_hash, :role)");

$sql->bindParam(':username', $user_name);
$sql->bindParam(':email', $email);
$sql->bindParam(':password_hash', $password_hidden);
$sql->bindParam(':role', $role);
if ($sql->execute()) {
    echo "<script type='text/javascript'>alert('New record created successfully!');</script>";
} else {
    echo "Error: could not insert the record";
}
    } catch (PDOException $e) {
        // Handle any errors with a custom message
        echo "Error: " . $e->getMessage();
    }
echo "<script type='text/javascript'>window.location.href = 'login_form.html';</script>";
exit;
}
?>
