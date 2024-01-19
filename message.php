<?php
session_start();

if (isset($_SESSION["user"]) && isset($_SESSION["category"])) {
    $category_name = $_SESSION["category"];
    $user = $_SESSION["user"];
    $conn = new mysqli("localhost", "root", "", "forumdb");

    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Post data
            $content = $_POST["user_input"];

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
            $stmt = $conn->prepare("INSERT INTO chat_message (user_id, cat_id, content) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $uid, $catid, $content);
            
            if ($stmt->execute()) {
                $stmt->close();

                // Fetch and return updated chat content
                $sql = "SELECT user.name, chat_message.content FROM chat_message
                INNER JOIN user ON user.userid = chat_message.user_id
                INNER JOIN test ON test.catid = chat_message.cat_id 
                WHERE chat_message.cat_id = '$catid'"
                ;

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
                } else {
                    $html = "Error: " . mysqli_error($conn);
                }

                echo $html;
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}
?>
