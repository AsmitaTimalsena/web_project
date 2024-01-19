<?php

session_start();

if (isset($_SESSION["user"])&& isset( $_SESSION["category"])) {
    $category_name = $_SESSION["category"];
    $user=$_SESSION["user"];
    $conn = new mysqli("localhost", "root", "", "forumdb");

    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    } 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <!-- Add this line in the head section of your HTML file -->
<script >
    function updateChat() {
    // Make an AJAX request to fetch new messages
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // Update the chat screen with the new messages
            document.getElementById("interaction").innerHTML = xmlhttp.responseText;
        }
    };

    xmlhttp.open("GET", "discussion.php", true);
    xmlhttp.send();
}


</script>
    <link rel="stylesheet" href="discussioncss.css">
</head>
<body>
    <div>
        <div class="navbar">
            <a href="home.php">Home</a>
            <a href="#"><?php 
            //chatta database bata tanne
            echo $category_name?></a>
            <div class="dropdown">
                <button class="dropbtn">Categories</button>
                <div class="dropdown-content">
                    
                <?php 
                     $sql = "SELECT cat_name from test ";
                     $result = $conn->query($sql);
                    
                     if ($result) {
                         while ($row = $result->fetch_assoc()) {
                            $category=$row['cat_name'];
                            echo "<div class='b' onclick='changeCategory(\"$category\")'>" . $row['cat_name'] . "&nbsp;</div>";

                        
                         }
                     }echo"</div>";
                    ?></div>
                   
               
            </div>
        </div>
        
        <div class="interaction" id="interaction">
            <div class="chat_screen" id="chat_screen">
            <?php


    // Define $stmt
    $stmt = null;

    // Get user ID
    $stmt = $conn->prepare("SELECT userid FROM user WHERE name = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->bind_result($uid);
    $stmt->fetch();
    $stmt->close();

    // Get category ID
    $stmt = $conn->prepare("SELECT catid FROM test WHERE cat_name = ?");
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $stmt->bind_result($catid);
    $stmt->fetch();
    $stmt->close();

    // Insert new message
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = $_POST["user_input"];
        $stmt = $conn->prepare("INSERT INTO chat_message (user_id, cat_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $uid, $catid, $content);

        if ($stmt->execute()) {
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Fetch and return updated chat content
    $sql = "SELECT user.name, chat_message.content FROM chat_message
            INNER JOIN user ON user.userid = chat_message.user_id
            INNER JOIN test ON test.catid = chat_message.cat_id 
            WHERE chat_message.cat_id = '$catid'";

    $result = mysqli_query($conn, $sql);

    // Build HTML for new messages
    $html = "";
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['name'] == $user) {
                $html .= " ME" . "<br>";
            } else {
                $html .= "UserName: " . $row['name'] . "<br>";
            }
            $html .= "&nbsp &nbsp " . $row['content'] . "<br>";
            $html .= "----------------------------------------------------------------------------------------------------<br>";
        }
        echo $html;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    $conn->close();

?>

               
            </div>
            <div class="write_your">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <textarea name="user_input" placeholder="Write your message..."></textarea>
                    <br>
                    <input type="submit" value="Post" id="submit">
                </form>
            </div>
        </div>
    </div>
    <?php 
    session_abort();?>
</body>
</html>
