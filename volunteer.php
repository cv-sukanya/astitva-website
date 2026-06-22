<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $mob_no = htmlspecialchars(strip_tags(trim($_POST['mob_no'])));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    $to = 'info@astitva.org.in';
    $subject = "Volunteer form submission from $name";
    $body = "
        Name: $name\n
        Mobile No: $mob_no\n
        Email: $email\n
        Message: $message
    ";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $body, $headers)) {
        header("Location: thank-you.html");
        exit();
    } else {
        echo "There was a problem sending your message. Please try again.<br>";
        echo '<a href="index.html" style="color: blue; text-decoration: none;">Go back to the Homepage</a>';
    }
}
?>
