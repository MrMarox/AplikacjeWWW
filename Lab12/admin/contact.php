<?php

include('../cfg.php');

class Contact {
    function Show() {
        echo '<form action="contact.php" method="post">
                Imię: <input type="text" name="name"><br>
                E-mail: <input type="text" name="email"><br>
                Wiadomość: <textarea name="message"></textarea><br>
                <input type="submit" name="submit" value="Wyślij">
                <input type="submit" name="reset" value="Przypomnij hasło">
            </form>';
    }

    function SendMail() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $message = $_POST["message"];

            $to = "admin@example.com";
            $subject = "Wiadomość od $name";
            $headers = "Od: $email";

            mail($to, $subject, $message, $headers);
        }
    }

    function RemindPass() {
        global $pass;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset"])) {
            $to = "admin@example.com";
            $subject = "Przypomnienie hasła";
            $message = "Twoje hasło to: $pass";
            $headers = "Od: system@example.com";

            mail($to, $subject, $message, $headers);
        }
    }
}

$contact = new Contact();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $contact->SendMail();
    } elseif (isset($_POST["reset"])) {
        $contact->RemindPass();
    }
} else {
    $contact->Show();
}
?>


