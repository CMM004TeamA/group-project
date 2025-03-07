<?php
require_once "connect.php";


//database check for email
$stmt = $conn ->prepare("SELECT email FROM users WHERE email = ?");
$stmt ->bind_param("s", $email);
$stmt ->execute();
$stmt->store_result();

if ($stmt->num_rows>0){
echo "This user is already registered";
$stmt->close();
}
else {
$sql = $conn->prepare("INSERT INTO users (username, email, password_hash,role)
                VALUES (?, ?, ?, ?)");
$sql->bind_param("ssss", $user_name, $email, $password_hidden, $role);
if ($sql->execute() === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql->error . "<br>" . $conn->error;
}
$sql->close();
header('Location: ../HTML/index_page.php');
exit;
}
?>