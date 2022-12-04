<!DOCTYPE html>
<html lang="de" data-theme="light">

<head>


    <!--
     █████   ██████ ██   ██ ████████ ██    ██ ███    ██  ██████  ██ 
    ██   ██ ██      ██   ██    ██    ██    ██ ████   ██ ██       ██ 
    ███████ ██      ███████    ██    ██    ██ ██ ██  ██ ██   ███ ██ 
    ██   ██ ██      ██   ██    ██    ██    ██ ██  ██ ██ ██    ██    
    ██   ██  ██████ ██   ██    ██     ██████  ██   ████  ██████  ██  
    ===============================================================
    
    BEIM VERÄNDERN DER FENSTERGRÖßE WEBSEITE RELOADEN!
    NICHT MEHR ALS 110% ZOOMEN, SONST FUNKTIONIERT DAS HIER NICHT MEHR!

-->

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PGWV Fehlersystem</title>

    <!-- === CSS === -->
    <link rel="stylesheet" href="./styles/indexOutput.css">

    <!-- === JQuery (https://releases.jquery.com) === -->
    <script src="./scripts/jQuery-3.6.6.js"></script>

    <!-- === RELOAD === -->
    <meta http-equiv="refresh" content="9000; url=#">

    <!-- === PHP === -->
    <?php
    // Install Dependencies: https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies
    
    require "vendor/autoload.php";
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
    $dotenv->load();

    // Lade Daten aus der Datenbank herunter
    $conn = mysqli_connect(getenv("TLIS1_HOSTNAME"), getenv("TLIS1_USERNAME"), getenv("TLIS1_PASSWORD"), getenv("TLIS1_DATABASE"));

    $sql = "SELECT * FROM problems WHERE (`last_update` > now() - INTERVAL 7 day OR (`status` >= 10 AND `status` <= 99)) ORDER BY raum ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Speichere Anzahl der Fehler
    $fehlerzahl = $result->num_rows;

    function getTranslation($type, $code)
    {
        global $conn;
        $sql = "SELECT * FROM translations WHERE type = ? AND code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $type, $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data['text'];
    }

    function getTranslationDescription($type, $code)
    {
        global $conn;
        $sql = "SELECT * FROM translations WHERE type = ? AND code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $type, $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data['description'];
    }
    ?>

</head>

<body class="font-sans min-h-screen">



    <!-- NAVBAR -->
    <div
        class="fixed w-full top-0 left-0 text-4xl text-white pl-5 pt-3 pb-5 bg-gradient-to-r from-[#0f0c29] via-[#302b63] to-[#24243e]">
        <h1 class="w-screen"><b>PGWV Fehlerportal</b><span class="inlineRight font-bold mr-8">
                <?php echo $fehlerzahl ?> Fehler
            </span>
        </h1>
    </div>

    <h1 class="pt-[4.5rem]"></h1>

    <!-- TABELLE -->
    <table class="w-full">
        <thead class="bg-slate-100 text-xs uppercase">
            <tr>
                <th class="text-center py-3" style="width: 7.5%">Raum</th>
                <th class="text-center py-3" style="width: 55%">Fehler</th>
                <th class="text-center py-3" style="width: 22.5%">Status</th>
                <!-- <th class="text-center py-3" style="width: 12.5%; max-width: 12.5%;">Melder</th> -->
                <th class="text-center py-3" style="width: 15%">Datum</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $currentId = 0;
            while ($problem = $result->fetch_assoc()) {
                // if($problem['status'] != "Gelöst"){
            ?>
            <tr class="text-sm <?php if ($problem['status'] == "100") {
                    echo "echo bg-green-100";
                } else if ($problem['status'] >= "20" && $problem['status'] <= "99") {
                    echo "echo bg-yellow-100";
                } else if ($problem['status'] >= "0" && $problem['status'] <= "19") {
                    echo "echo bg-red-100";
                } ?>">
                <td class="text-center py-4">
                    <?php echo $problem['raum']; ?>
                </td>
                <td class="text-center py-4"><b>
                        <?php echo getTranslation("problemType", $problem['kategorie']); ?>
                    </b>
                    <br>
                    <?php echo substr($problem['problembeschreibung'], 0, 1500); ?>
                </td>
                <td class="text-center py-4">
                    <?php echo getTranslation("problemStatus", $problem['status']); ?><br>
                </td>
                <!-- <td class="text-center py-4"><?php //echo $problem['melder']; S
                    ?></td> -->
                <td class="text-center py-4">
                    <?php echo date("d.m.Y H:i", strtotime($problem['datum'])) . " Uhr"; ?>
                </td>
            </tr>

            <?php
                $currentId += 1;
                // } // If not Gelöst
            } // DB  LOOP s
            ?>
        </tbody>
    </table>


    <!-- Footer -->
    <!-- <footer class="fixed w-full bottom-0 left-0">
        <div class="bg-gradient-to-r from-slate-300 to-gray-300 text-xs mt-1 p-2 w-full text-center">
            <p class="inline">Version: Beta 1.2.1
                (<?php echo "Letzte Aktualisierung: " . date("d.m.Y H:i:s", filemtime("index.php")); ?>)</p>
            <p class="inline"> | </p>
            <p class="inline">&#169; 2022 Justus Seeck & Joel Wiedemeier (Jahrgang 12, PGWV)</p>
        </div>
    </footer> -->


    <footer class="fixed w-full bottom-0 left-0">
        <div
            class="bg-gradient-to-r from-[#0f0c29] via-[#302b63] to-[#24243e] text-white text-xs mt-1 p-2 w-full text-center">
            <p class="inline">
                <?php echo "Letzte Aktualisierung: " . date("d.m.Y H:i:s", filemtime("index.php")); ?>
            </p>
            <p class="inline">|</p>
            <p class="inline">
                &#169; 2022 Justus Seeck & Joel Wiedemeier (Jahrgang 12, PGWV)
            </p>
            <img class="inlineRight h-4 mr-2" src="https://skillicons.dev/icons?i=html,css,php,javascript"
                alt="Astro, HTML, CSS, JavaScript" />
        </div>
    </footer>

    <div class="py-[16px]"></div>

    <!-- ============================================================================================================================================== -->

    <!-- === JavaScript === -->
    <script>
        // CONFIGURATION:
        let enableScrolling = true;

        if (enableScrolling) {
            $(document).ready(function () {

                var isNotScrolling = true;
                var ort = 0

                function scrollDone() {
                    // alert("Scroll Done")
                    setTimeout(() => {
                        window.scrollTo({
                            top: 0,
                            behavior: "smooth"
                        });
                    }, 5000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 6000);
                }

                function scrollDownUntilEndOfPage() {
                    if (isNotScrolling) {
                        scrollDone();
                        return
                    } else {
                        setTimeout(() => {
                            ort += 1
                            window.scrollTo({
                                top: ort,
                                behavior: "auto"
                            });
                            scrollDownUntilEndOfPage();
                        }, 12);
                    }

                }

                // REAGIERT NUR, WENN WIRKLICh GESCROLLT WIRD
                // TODO: Check einbauen, der reload triggert, auch wenn die seite nicht gescrollt wird
                window.onscroll = function () {
                    if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
                        isNotScrolling = true;
                        // alert("At the bottom!")
                    }
                }

                // Check if there is no scroll bar
                // SOURCE: https://stackoverflow.com/a/2146903
                if ($("body").height() <= $(window).height()) {
                    // alert("NO Vertical Scrollbar! D:");
                    scrollDone();
                }


                // ======================================================================================

                setTimeout(() => {
                    isNotScrolling = false;
                    scrollDownUntilEndOfPage();
                }, 4000);
            });
        }
    </script>

</body>

<!-- Website by
.------------------------------------.
|..    *    .  .   *  .  ..   .    . |
|*    .      .     .      *  .   ()  |
|   .  . *   .   /\  *    .    ..  . |
| .   .     -+- | A|    *    .   .   |
|  .  .  *   '  | S| ..   .   .   . .|
|..    .        | T|  . .     .    . |
|     *  ()  .  | R|    ..   -+-   . |
|*  .  .   .    | A|    .     '  .  .|
| .   .  *   .  | G| *  .   .   .  * |
|. .    .  .    | O|      .   .    . |
|..    .    .   '--'  .  ..     . .  |
|     * ..   .  /\/\      .   *   .  |
|  .   .     .  '.''   .   ()   .  . |
| -+- .  *  .   .'.' *  .     *   .  |
|  '  .  ..   .  ' .   .   *    .    |
'-------------AstragoDE--------------'
-->

</html>