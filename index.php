<!DOCTYPE html>
<html lang="de" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PGWV Fehlersystem</title>


    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Open Graph & Social Media Tags ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->


    <!-- === Meta Tags for Social Media and SEO === -->
    <!-- = Info: https://ogp.me/#types = -->
    <!-- = GENERAL = -->
    <!-- <meta property="og:site_name" content="TEMPLATE" />
    <meta property="og:title" content="TEMPLATE" />
    <meta property="og:description" content="TEMPLATE" />
    <meta property="og:type" content="TEMPLATE" />
    <meta property="og:url" content="TEMPLATE" />
    <meta property="og:image" content="TEMPLATE" /> -->
    <!-- = TWITTER = -->
    <!-- <meta name="twitter:title" content="TEMPLATE" />
    <meta name="twitter:description" content="TEMPLATE" />
    <meta name="twitter:image" content="TEMPLATE" />
    <meta name="twitter:card" content="TEMPLATE" /> -->


    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Design ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->


    <!-- = THEME = -->
    <!-- <meta name="theme-color" content="#16a34a" /> -->


    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ JavaScript Frameworks ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->


    <!-- === JQuery (https://releases.jquery.com) === -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->


    <!-- === py-script (https://pyscript.net) === -->
    <!-- <link rel="stylesheet" href="https://pyscript.net/alpha/pyscript.css" />
    <script defer src="https://pyscript.net/alpha/pyscript.js"></script>
    <py-env>
        -
    </py-env> -->


    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Design Frameworks ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->


    <!-- === Tailwind CSS (https://tailwindcss.com/docs/customizing-colors#) === -->
    <!-- <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: {
                preflight: true,
            },
        }
    </script> -->


    <!-- === DaisyUI (https://daisyui.com/components) === -->
    <!-- Added Via Tailwind -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/daisyui@2.17.0/dist/full.css" rel="stylesheet" type="text/css" /> -->


    <!-- === Material Design (https://material.io/components?platform=web & https://m3.material.io/components/buttons/implementation/web ; https://material.io/develop/web/getting-started) === -->
    <!-- <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> -->


    <!-- === 98.css (https://jdan.github.io/98.css/#components) === -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/98.css"> -->


    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Customization ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

    <link rel="stylesheet" href="indexOutput.css">


    <!-- === JavaScript === -->
    <script>
        $(document).ready(function() {
            // jQuery methods go here...
        });

        // window.scrollTo({
        //     top: 500,
        //     behavior: "smooth"
        // });
    </script>

    <!-- === CSS === -->
    <!-- <style>
        
    </style> -->

    <?php
    $conn = mysqli_connect('5.182.206.4', 'Justus', 'oNvNOsDT)19Mpx-m', 'fehlersystem');
    ?>


</head>

<body class="font-sans">

    <!-- NAVBAR -->
    <div class="header text-4xl font-bold text-white bg-red-600 pl-5 pt-3 pb-5">
        <h1>PGWV Fehlersystem</h1>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th class="text-center" style="width: 7.5%;">Raum</th>
                <th class="text-center fehlerCell">Fehler</th>
                <th class="text-center" style="width: 22.5%;">Status</th>
                <th class="text-center" style="width: 12.5%;">Melder</th>
                <th class="text-center" style="width: 15%;">Datum</th>
            </tr>
        </thead>
        <tbody id="problemTable">
            <?php

            $sql = "SELECT * FROM problems WHERE 'status' != 'gelöst' AND 'status' != 'spam' ORDER BY raum ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($problem = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td class="text-center"><?php echo $problem['raum']; ?></td>
                    <td class="text-center"><b><?php echo $problem['kategorie']; ?></b> <br> <?php echo $problem['problembeschreibung']; ?></td>
                    <td class="text-center"><?php echo $problem['status']; ?><br></td>
                    <td class="text-center"><?php echo $problem['melder']; ?></td>
                    <td class="text-center"><?php echo $problem['datum']; ?></td>
                </tr>

            <?php
            } // DB  LOOP
            ?>
        </tbody>
    </table>

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