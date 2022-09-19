<?php
session_start();
$conn = mysqli_connect('5.182.206.4', 'Justus', 'oNvNOsDT)19Mpx-m');

$TelegramToken = "5476043387:AAHLSjOUnxXlxTvsjvpNiTk_M451yqz9xtI";
$chat_id = "-715106725";

function getUserName($id)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return array("firstname" => $data['firstname'], "lastname" => $data['lastname']);
}

function telegram_sendMessageToGroup($text)
{
    global $TelegramToken;
    global $chat_id;
    $text = urlencode($text);
    file_get_contents("https://api.telegram.org/bot$TelegramToken/sendMessage?chat_id=$chat_id&text=$text");
}

function verifyLogin()
{
    global $conn;
    $loginname = $_SESSION['loginname'];
    if (isset($_SESSION['userid']) && isset($_SESSION['password'])) {
        try {
            $sql = "SELECT * FROM users WHERE loginname = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $loginname);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
        } catch (Exception $e) {
            die("Login ist zurzeit nicht möglich <br>Error: " . $e);
        }
        if (password_verify($_SESSION['password'], $user['password'])) {
            // echo "Passwort ist korrekt<br>";
            $_SESSION['userid'] = $user['id'];
            return true;
        } else {
            redirectToLogin();
            return false;
        }
    } else {
        return false;
    }
}

function verifyStayLoggedIn()
{
    global $conn;
    //Überprüfe auf den 'Angemeldet bleiben'-Cookie
    if (!isset($_COOKIE['identifier']) && !isset($_COOKIE['securitytoken'])) {
        return true;
    }

    if (isset($_SESSION['userid']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
        $identifier = $_COOKIE['identifier'];
        $securitytoken = $_COOKIE['securitytoken'];

        $sql = "SELECT * FROM securitytokens WHERE identifier = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $securitytoken_row = $result->fetch_assoc();
        $stmt->close();

        if (sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
            $sql = "DELETE  FROM securitytokens WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['userid']);
            $stmt->execute();
            $stmt->close();

            session_destroy();
            setcookie("identifier", "", time() - (3600 * 24 * 365), "/");
            setcookie("securitytoken", "", time() - (3600 * 24 * 365), "/");

            die('Ein vermutlich gestohlener Security Token wurde identifiziert - Du wurdest sicherheitshalber von allen Geräten abgemeldet!');
            return false;
        } else { //Token war korrekt         
            //Setze neuen Token
            // echo "Token war korrekt";
            $neuer_securitytoken = random_string();

            $sql = "UPDATE securitytokens SET securitytoken = ? WHERE identifier = ?";
            $stmt = $conn->prepare($sql);
            $sha1 = sha1($neuer_securitytoken);
            $stmt->bind_param("ss", $sha1, $identifier);
            $stmt->execute();
            $stmt->close();

            setcookie("identifier", $identifier, time() + (3600 * 24 * 365), "/"); //1 Jahr Gültigkeit
            setcookie("securitytoken", $neuer_securitytoken, time() + (3600 * 24 * 365), "/"); //1 Jahr Gültigkeit

            //Logge den Benutzer ein
            $_SESSION['userid'] = $securitytoken_row['user_id'];
            return true;
        }
    } else {
        return false;
    }

    if (!isset($_SESSION['userid'])) {
        die('Bitte zuerst <a href="/admin/login">Einloggen</a>');
    }
}

function callSetup($reason)
{
    header("refresh:5; url=/setup?setup_called=true");
?>

    <body onload="countDown(5)">
        <h1><?php echo $reason; ?></h1>
        <script>
            function countDown(seconds) {
                var element = document.getElementById("countdown");
                clearInterval(intervalId);
                var intervalId = setInterval(function() {
                    seconds = seconds - 1;
                    element.innerHTML = seconds;
                }, 1000);
            }
        </script>
        <h3>Die Datenbank fehlersystem noch existiert nicht. Sie werden nun zum Installationsassistenten weitergeleitet.! <br>Weiterleitung in <b onload="countDown(5)" id="countdown">5</b> Sekunden!</h3>
    </body>
<?php
}

if ($_GET['setup_called'] != true) { // If the setup script is not called, then Power-On-Self-Test!
    // TEST FOR DATABASE
    try {
        mysqli_select_db($conn, 'fehlersystem');
    } catch (Exception $e) { // Wenn die Datenbank nicht existiert, dann rufe den Setup-Assistenten auf!
        $error = strtolower($e);
        if (str_contains($error, 'unknown database')) {
            callSetup("Die Database fehlersystem existiert nicht!");
        }
    }
    // TEST FOR users TABLE
    try {
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) { // Wenn die Tabelle nicht existiert, dann rufe den Setup-Assistenten auf!
        $error = strtolower($e);
        if (str_contains($error, 'unknown table')) {
            die("Die Tabelle users existiert nicht - Bitte melde dich bei Herr Gaida!");
        }
    }
}

if (!$conn) {
    die("<b>Connection failed:</b> " . mysqli_connect_error());
}

function random_string()
{ // Random String Generator, required for the security token and identifier

    if (function_exists('random_bytes')) {
        $bytes = random_bytes(16);
        $str = bin2hex($bytes);
    } else if (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
    } else {
        //Bitte euer_geheim_string durch einen zufälligen String mit >12 Zeichen austauschen
        $str = md5(uniqid('herrgaidaistgenevertweiljederlehrerihnvordemlehrerzimmerabfängt', true));
    }
    return $str;
}

function verifyPermission($requiredPermission)
{
    global $conn;
    $userid = $_SESSION['userid'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_row = $result->fetch_assoc();
    $stmt->close();
    if ($user_row['permission_level'] <= $requiredPermission) {
        return true;
    } else {
        return false;
    }
}

function redirectToLogin()
{
    header("Location: /admin/login");
    die();
}

?>