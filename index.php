<!DOCTYPE html>
<html lang="de" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PGWV Fehlersystem</title>

    <!-- === CSS === -->
    <!-- <style>
        </style> -->
    <link rel="stylesheet" href="indexOutput.css">

    <!-- === RELOAD === -->
    <meta http-equiv="refresh" content="1800; url=#">

    <!-- === PHP === -->
    <?php
    // Lade Daten aus der Datenbank herunter
    $conn = mysqli_connect('5.182.206.4', 'Justus', 'oNvNOsDT)19Mpx-m', 'fehlersystem');

    $sql = "SELECT * FROM problems WHERE 'status' != 'gelöst' AND 'status' != 'spam' ORDER BY raum ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Speichere Anzahl der Fehler
    $fehlerzahl = $result->num_rows;
    ?>


</head>

<body class="font-sans">

    <!-- NAVBAR -->
    <div class="sticky z-50 text-4xl font-bold text-white bg-red-600 pl-5 pt-3 pb-5">
        <h1 style="width: 100vw;">PGWV Fehlersystem <span class="inlineRight mr-16"><?php echo $fehlerzahl ?> Fehler</span>
        </h1>
    </div>

    <!-- TABELLE -->
    <table class="table mt-20 w-full z-1">
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
                <tr id="<?php echo $currentId; ?>">
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


    <!-- ================================================================================================================== -->

    <!-- TODO:
    - Clip Text if it is too long
    - make table responsive
-->


    <!-- === JavaScript === -->
    <script>
        // $(document).ready(function() {
        //     // jQuery methods go here...
        // });



        var isNotScrolling = true;
        var ort = 0

        // ======================================================================================

        function scrollDone() {
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

        window.onscroll = function() {
            if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
                isNotScrolling = true;
                // alert("At the bottom!")
            }
        }

        // ======================================================================================

        setTimeout(() => {
            isNotScrolling = false;
            scrollDownUntilEndOfPage();
        }, 5000);
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