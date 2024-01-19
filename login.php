<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "forumdb");

if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $password = $_POST['password'];

        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT name, password FROM user WHERE name = '$name'");
        $stmt->execute();
        $stmt->bind_result($dbUsername, $dbPassword);
        $stmt->fetch();
        $stmt->close();

        // Verify the entered password against the hashed password from the database
        if ($dbUsername== $name && $password== $dbPassword) {
            echo "Login Successful";
            $_SESSION['user']=$name;
            header("Location: home.php");
        } else {
            echo "Invalid username or password";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
     <style> 
        #submit {
    padding: 10px;
    border: 1px solid lightsalmon;
    background-color: lightcyan;
    box-shadow: 5px 10px 18px lightcyan;
    width: 55%;
    margin: 20px; margin-left:165px;
    color: limegreen;
    font-size: 30px;
    font-weight: bolder;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}
 body {
    background-color: lightslategray;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh; /* Ensure the full height of the viewport is covered */
    margin: 0;
}
#password,#name  {
    height: 45px;
    width: 250px;
    border: 1px solid lightsalmon;
    font-size: 30px;       
    font-weight: bolder;
    color: limegreen;
    box-shadow: 5px 10px 18px lightcyan;
    box-sizing: border-box;
}
.style1 {
    color: lightgoldenrodyellow;
    font-size: 30px;
    font-weight: bolder;
    text-align: center; /* Center the text within the container */
}
.style2{
    margin-left: 38px;
}
.style4{
    margin-left: 100px;
    font-size: 50px;
}

    </style>
</head>
<body>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
         <div class ="style1">
            <div class ="style4"> LOGIN FROM</div><br><br>
      <div class="style2">
       Name: <input type="text" name="name" id="name"><br><br>
       </div>
        Password:  <input type="password" name="password" id="password"><br><br>
        <input type="submit" name="submit" id="submit">
    </div>
    </form>
</body>
</html>
