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

