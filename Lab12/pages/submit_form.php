<?php
if (isset($_POST['submit'])) {
    // Zmienne z danymi z formularza
    $to = 'marcelsobiecki@yahoo.com'; // Zmień na właściwy adres e-mail, na który mają być wysyłane wiadomości
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
} else {
    // Jeżeli próba dostępu do pliku bez przesłania formularza
    header("Location: index.html"); // Przekieruj użytkownika na stronę główną lub inną
    exit();
}
?>