<!DOCTYPE html>
<!-- <html lang="de" data-theme="dark"> -->
<html lang="de" data-theme="light">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PGWV Fehlersystem</title>

    <!-- === CSS === -->
    <link rel="stylesheet" href="./styles/indexOutput.css">

    <!-- === JQuery (https://releases.jquery.com) === -->
    <script src="./scripts/jQuery-3.6.6.js"></script>

    <!-- === RELOAD === -->
    <meta http-equiv="refresh" content="5; url=#">

    <!-- === PHP === -->
    <?php
    // Install Dependencies: https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies

    require "vendor/autoload.php";
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
    $dotenv->load();

    // Lade Daten aus der Datenbank herunter
    $conn = mysqli_connect(getenv("TLIS1_HOSTNAME"), getenv("TLIS1_USERNAME"), getenv("TLIS1_PASSWORD"), getenv("TLIS1_DATABASE"));

    $sql = "SELECT * FROM problems WHERE 'status' != 'Gelöst' AND 'status' != 'Spam' ORDER BY raum ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Speichere Anzahl der Fehler
    $fehlerzahl = $result->num_rows;
    ?>

</head>

<body class="font-sans min-w-screen">

    <!-- HEADER -->
    <header>
        <div class="w-full text-4xl text-white pl-5 pt-3 pb-5 bg-red-600">
            <h1 class="w-screen"><b>PGWV Fehlerportal</b><span class="inlineRight font-bold mr-8"><?php echo $fehlerzahl ?> Fehler</span></h1>
        </div>
    </header>   
</body>

</html>