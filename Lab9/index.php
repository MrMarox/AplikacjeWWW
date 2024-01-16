<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <meta name="Author" content="Marceli Sobiecki" />
    <link rel="stylesheet" href="./styles/global.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <title>Test</title>
</head>

<body>
<body>
    <div class="overflow-x">
        <div class="info">
            <p>Strona na laby z AplikacjiWWW</p>
        </div>
        <header class="header">
            <a href="index.php?page=landing">
                <img src="image/logo.png" alt="website-logo" />
            </a>
            <ul class="header__menu">
                <a href="index.php?page=samochody">
                    <li class="header__menu-item">Samochody</li>
                </a>
                <a href="index.php?page=video">
                <li class="header__menu-item">Nagrania</li>
                </a>
                <a href="index.php?page=onas">
                    <li class="header__menu-item">O nas</li>
                </a>
                <a href="index.php?page=kontakt">
                    <li class="header__menu-item">Kontakt</li>
                </a>
                <a href="./admin/admin.php">
                    <li class="header__menu-item">Admin</li>
                </a>
                <a href="koszyk.php">
                    <li class="header__menu-item">Koszyk</li>
                </a>
            </ul>
        </header>
        <div class="dynamic">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'landing';
            include('pages/'. $page . '.php');
            ?>
        </div>
        </div>
</body>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        delay: 200,
        duration: 1200,
        once: true
    });
</script>

</html>