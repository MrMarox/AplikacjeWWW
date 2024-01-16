<?php
if (isset($_POST['submit'])) {
    // Zmienne z danymi z formularza
    $to = 'marcelsobiecki@yahoo.com';
    $subject = 'Wiadomość z formularza kontaktowego';
    
    // Pobierz dane z formularza
    $name = $_POST['FName'];
    $lastname = $_POST['LName'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $message_content = $_POST['Message'];

    // Treść wiadomości e-mail
    $message = "Imię: $name\n";
    $message .= "Nazwisko: $lastname\n";
    $message .= "Email: $email\n";
    $message .= "Numer Telefonu: $phone\n\n";
    $message .= "Wiadomość:\n$message_content";

    // Nagłówki e-maila
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Wysłanie e-maila
    mail($to, $subject, $message, $headers);

    // Komunikat potwierdzający
    echo "Dziękujemy za wysłanie formularza! Odpowiemy najszybciej, jak to możliwe.";
}
?>

<div class="content" data-aos="zoom-out-up">
    <div class="form">
        <p class="form-title">Daj nam znać jak możemy ci pomóc</p>
        <p class="form-text">Uzupełnij wszystkie puste pola, opisz swój problem, a my postaramy się odpowiedzieć tobie najszybciej, jak potrafimy.</p>
        <div class="form-container">
            <form action="kontakt.php" method="post">
                <div class="top">
                    <div class="top-left">
                        <input type="text" name="FName" placeholder="Imię" id="FName" required />
                        <input type="email" name="Email" placeholder="Email" id="Email" required />
                    </div>
                    <div class="top-right">
                        <input type="text" name="LName" placeholder="Nazwisko" id="LName" required />
                        <input type="tel" name="Phone" placeholder="Numer Telefonu" id="Phone" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required />
                    </div>
                </div>
                <div class="textarea">
                    <textarea name="Message" placeholder="Napisz w czym jest problem" id="textarea" required></textarea>
                </div>
                <input class="sub" type="submit" name="submit" value="Wyślij">
            </form>
        </div>
    </div>
</div>