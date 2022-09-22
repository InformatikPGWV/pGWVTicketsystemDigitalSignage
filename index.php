<!DOCTYPE html>
<!-- <html lang="de" data-theme="dark"> -->
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
    <link rel="stylesheet" href="indexOutput.css">

    <!-- === JQuery (https://releases.jquery.com) === -->
    <script src="jQuery-3.6.6.js"></script>

    <!-- === RELOAD === -->
    <meta http-equiv="refresh" content="1800; url=#">

    <!-- === PHP === -->
    <?php
    // Lade Daten aus der Datenbank herunter
    $conn = mysqli_connect('5.182.206.4', 'Justus', 'oNvNOsDT)19Mpx-m', 'fehlersystem');

    $sql = "SELECT * FROM problems WHERE 'status' != 'Gelöst' AND 'status' != 'Spam' ORDER BY raum ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Speichere Anzahl der Fehler
    $fehlerzahl = $result->num_rows;
    ?>

</head>

<body class="font-sans">

    <!-- NAVBAR -->
    <div class="sticky z-50 text-4xl text-white pl-5 pt-3 pb-5 bg-gradient-to-r from-red-600 to-red-600">
        <h1 style="width: 100vw;"><b>PGWV Fehlersystem</b><span class="inlineRight font-bold mr-8"><?php echo $fehlerzahl ?> Fehler</span>
        </h1>
    </div>

    <!-- Dummy Element für Abstand -->
    <h1 class="pt-20"></h1>

    <!-- TABELLE -->
    <table class="table w-full z-1">
        <thead>
            <tr>
                <th class="text-center z-1" style="width: 7.5%; max-width: 7.5%;">Raum</th>
                <th class="text-center z-1" style="width: 55%; max-width: 55%;">Fehler</th>
                <th class="text-center z-1" style="width: 22.5%; max-width: 22.5%;">Status</th>
                <!-- <th class="text-center z-1" style="width: 12.5%; max-width: 12.5%;">Melder</th> -->
                <th class="text-center z-1" style="width: 15%; max-width: 15%;">Datum</th>
            </tr>
        </thead>
        <tbody id="problemTable">
            <?php
            $currentId = 0;
            while ($problem = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td class="text-center"><?php echo $problem['raum']; ?></td>
                    <td class="text-center"><b><?php echo $problem['kategorie']; ?></b> <br> <?php echo substr($problem['problembeschreibung'], 0, 100); ?></td>
                    <td class="text-center"><?php echo $problem['status']; ?><br></td>
                    <!-- <td class="text-center"><?php //echo $problem['melder']; S
                                                    ?></td> -->
                    <td class="text-center"><?php echo $problem['datum']; ?></td>
                </tr>
            <?php
                $currentId += 1;
            } // DB  LOOP 
            ?>
        </tbody>
    </table>

    <!-- Footer -->
    <footer>
        <div class="bg-gradient-to-r from-slate-300 to-gray-300 text-s mt-1 p-2 w-full text-center">
            <p>Version: Beta 1.0.0 (<?php echo "Letzte Aktualisierung: " . date("d.m.Y H:i:s", filemtime("index.php")); ?>)</h1>
            <p>&#169; 2022 Justus Seeck & Joel Wiedemeier (Jahrgang 12, PGWV)</h1>
        </div>
    </footer>

    <!-- ============================================================================================================================================== -->

    <!-- === JavaScript === -->
    <script>
        // CONFIGURATION:
        let enableScrolling = true;

        if (enableScrolling) {
            $(document).ready(function() {

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
                window.onscroll = function() {
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
                }, 5000);
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