<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- === CSS === -->
    <link rel="stylesheet" href="./styles/indexOutput.css">

    <!-- === JQuery (https://releases.jquery.com) === -->
    <script src="./scripts/jQuery-3.6.6.js"></script>


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

<body class="h-screen">

    <!-- TABLE -->
    <iframe class="w-full h-[96.5vh]" src="./table.php" frameborder="0"></iframe>


    <!-- Footer -->
    <footer class="w-full h-[3.5vh] m-0 p-0">
        <div class="bg-gradient-to-r from-slate-300 to-gray-300 text-xs mt-1 p-2 w-full h-full text-center">
            <p class="inline">Version: Beta 1.2.1
                (
                <?php echo "Letzte Aktualisierung: " . date("d.m.Y H:i:s", filemtime("index.php")); ?>)
            </p>
            <p class="inline"> | </p>
            <p class="inline">&#169; 2022 Justus Seeck & Joel Wiedemeier (Jahrgang 12, PGWV)</p>
        </div>
    </footer>
</body>

</html>