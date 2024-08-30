<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($query = $conn->prepare("SELECT ID_Personne, Role FROM Personne WHERE Email = ? AND Mot_de_Passe = ?")) {
        $hashed_password = md5($password); 
        $query->bind_param("ss", $email, $password);
        $query->execute();
        $query->store_result();

        $query->bind_result($id_personne, $role);
        while ($query->fetch()) {
            echo "ID_Personne: $id_personne, Role: $role";
        }
        
        if ($query->num_rows == 1) {
            $query->bind_result($user_id, $role);
            $query->fetch();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            header("Location: dashboard.html");
        } else {
            echo "Invalid credentials.";
        }

        $query->close();
    } else {
        echo "Error in preparing statement: " . $conn->error;
    }
}
?>

<!--<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>-->
