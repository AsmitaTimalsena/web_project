<?php 
session_start();

if (isset($_SESSION["user"])) {
    $name = $_SESSION["user"];
    $conn = new mysqli("localhost", "root", "", "forumdb");

    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Corrected code to use bind_param for the SELECT query
            $stmt = $conn->prepare("SELECT userid FROM user WHERE name = ?");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $stmt->bind_result($uid);
            $stmt->fetch();
            $stmt->close();

            $category = $_POST['cat_name'];
            $description = $_POST['description'];

            $sql = "INSERT INTO test (uid, cat_name, description) VALUES ('$uid', '$category', '$description')";
            $result = $conn->query($sql);

            if ($result) {
                echo "Successfully created new category";
                $_SESSION['category'] = $category;
                header('location:discussion.php');
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
    <style> 
        body {
    background-color: lightslategray;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh; /* Ensure the full height of the viewport is covered */
    margin: 0;
}

.style1 {
    color: lightgoldenrodyellow;
    font-size: 30px;
    font-weight: bolder;
    text-align: center; /* Center the text within the container */
}

#submit {
    padding: 10px;
    border: 1px solid lightsalmon;
    background-color: lightcyan;
    box-shadow: 5px 10px 18px lightcyan;
    width: 60%;
    margin: 20px; margin-left: 215px;
    color: limegreen;
    font-size: 30px;
    font-weight: bolder;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

#cat_name,#description  {
    height: 45px;
    width: 250px;
    border: 1px solid lightsalmon;
    font-size: 30px;       
    font-weight: bolder;
    color: limegreen;
    box-shadow: 5px 10px 18px lightcyan;
    box-sizing: border-box;
}
.style2{
    margin-left: 50px;
}

    </style>
</head>
<body>
    <form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class ="style1">
        Category Name:
        <input type="text" name="cat_name" id="cat_name"><br><br>
        <div class="style2">
        Description:
        <input type="text" name="description" id="description"><br><br></div>

        <input type="submit" name="submit" id="submit"><br><br>
    </div>
    </form>
</body>
</html>