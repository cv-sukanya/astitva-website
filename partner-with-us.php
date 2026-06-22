<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* ==========================
       1. HONEYPOT CHECK
    ========================== */
    if (!empty($_POST['website'])) {
        die("Spam detected.");
    }

    /* ==========================
       2. GOOGLE reCAPTCHA CHECK
    ========================== */
    $secretKey = "6Lcf5akrAAAAAGQ1R4PBBkhf2NZUclV1nY_NTcFm"; // Replace with your reCAPTCHA secret key
    $captchaResponse = $_POST['g-recaptcha-response'];

    if (empty($captchaResponse)) {
        die("Captcha not completed.");
    }

    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captchaResponse);
    $captchaData = json_decode($verifyResponse);

    if (!$captchaData->success) {
        die("Captcha verification failed.");
    }

    /* ==========================
       3. RATE LIMIT (1 per minute per IP)
    ========================== */
    $ip = $_SERVER['REMOTE_ADDR'];
    $logFile = __DIR__ . "/ip_logs/$ip.txt";

    if (!is_dir(__DIR__ . "/ip_logs")) {
        mkdir(__DIR__ . "/ip_logs", 0777, true);
    }

    if (file_exists($logFile) && time() - filemtime($logFile) < 60) {
        die("You can only submit once per minute.");
    }
    file_put_contents($logFile, "");

    /* ==========================
       4. SANITIZE INPUT
    ========================== */
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $company_name = htmlspecialchars(strip_tags(trim($_POST['company_name'])));
    $mob_no = htmlspecialchars(strip_tags(trim($_POST['mob_no'])));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    $to = 'info@astitva.org.in';
    $subject = "Partner with us form submission from $name";
    $body = "
        Name: $name\n
        Company Name: $company_name\n
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
