<?php 
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: home.php");
}

if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    $conn = new mysqli("localhost", "root", "", "forumdb");

    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $category = $_POST['selected_category'];

    $_SESSION['category'] = $category;

    header('Location: discussion.php');
    exit();
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Options</title>
   <style> 

body {
    background-color: white;
    font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    display: flex;


    height: 100vh;
    text-align: center;
}

h1 {
    color: black;
}

div.container {
    box-align: center;
    background-color: white;
    padding: 14px 56px;
    margin: auto;

    border-radius: 20px;
}

.create_cat {
    padding: 14px 56px;
    border: 1px solid black;
    float: left;
    margin: 14px 45px 14px 45px;
    border-radius: 20px;
    background-color: purple;
}
select{
   
    border:none;
   
   font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
  
    background-color: inherit;
    color: white;
}
option{
    font-size: 40px;
    color: black;
}
.select {
    padding: 14px 56px;
    border: 1px solid black;
    margin: 14px 45px 14px 45px;
    float: left;
    border-radius: 20px;
    background-color: purple;
    color: white;
}

.create_cat:hover {
    background-color: green;

    box-shadow: 10px 10px lightgreen;
}

.select:hover {
    background-color: green;
    box-shadow: 10px 10px lightgreen;
}

a {
    text-decoration: none;
    color: white;
}

.username {
    color: white;


}

   </style>
</head>

<body>
    <?php 
    $realUsername = "Anush";
    ?>

    <div class="username">Hey,
        <?php echo $realUsername; ?>
    </div>

    <div class="container">
        <h1>Welcome to my forum Website!</h1>
        <div class="create_cat"><a href="create_catform.php">Create New Category</a></div>
        <div class="select">
            <form action="<? /*php echo $_SERVER['PHP_SELF']; ?>" method="post" id="categoryForm">
                <?php 
                // category ko name fetch garera select gariyeko category ko name laai store garne
                $sql = "SELECT cat_name from test ";
                $result = $conn->query($sql);
                echo "<select name='selected_category' id='select' onchange='this.form.submit()'>";
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option>" . $row['cat_name'] . "</option>";
                    }
                }
                echo "</select>"; */
                ?>
            </form>
        </div>
    </div>

</body>

</html>
