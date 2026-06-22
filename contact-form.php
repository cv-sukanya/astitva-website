<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $mob_no = htmlspecialchars(strip_tags(trim($_POST['mob_no'])));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    $to = 'info@astitva.org.in';
    $subject = "Contact Form Submission from $name";
    $body = "
        Name: $name\n
        Mobile No: $mob_no\n
        Email: $email\n
        Message:\n$message
    ";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "Thank you, $name. Your message has been sent!";
    } else {
        echo "There was a problem sending your message. Please try again.";
    }
}
?>
