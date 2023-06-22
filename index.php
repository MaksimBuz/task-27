<?php

use Monolog\Handler\StreamHandler;

session_start();
// Параметры приложения
$clientId     = '51685278'; 
$clientSecret = '9Xlg2B8YS60YsWtcwc0r'; 
$redirectUri  = 'http://task-27/index.php';

// Формируем ссылку для авторизации
$params = array(
    'client_id'     => $clientId,
    'redirect_uri'  => $redirectUri,
    'response_type' => 'code',
    'display' => 'page',
    'v'             => '5.126',
    'scope'         => 'photos,offline',
);

?>

<h1>Форма регистрации</h1>
<form method="post" action="./register.php">
    <input type="text" name="login" placeholder="Логин"><br />
    <input type="password" name="pass"> <br />
    <!-- <input type="hidden" name="token" value="<?= $token ?>"> <br/> -->
    <input type="submit" value="Зарегестрироваться">
</form>
<?php

if (!$_SESSION["isauth"] ) {
    $token = hash('gost-crypto', random_int(0, 999999));
    $_SESSION["CSRF"] = $token;
?>
    <h1>Форма авторизации</h1>
    <form method="post" action="./authorization.php">
        <input type="text" name="login" placeholder="Логин"><br />
        <input type="password" name="pass"> <br />
        <input type="hidden" name="token" value="<?= $token ?>"> <br />
        <input type="submit" value="Войти">
    </form>
<?php
}
?>

<button><?= '<a href="http://oauth.vk.com/authorize?' . http_build_query($params) . '">Авторизация через ВКонтакте</a>' ?> </button>
<?php

$params = array(
    'client_id'     => '51685278',
    'client_secret' => '9Xlg2B8YS60YsWtcwc0r',
    'code'          => $_GET['code'],
    'redirect_uri'  => 'http://task-27/index.php'
);

if (!$content = @file_get_contents('https://oauth.vk.com/access_token?' . http_build_query($params))) {
}

$response = json_decode($content);

//А вот здесь выполняем код, если все прошло хорошо
if (isset($response)) {
    $token = $response->access_token; 
    $expiresIn = $response->expires_in; 
    $userId = $response->user_id; 
// Сохраняем токен в сессии
$_SESSION['token'] = $token;
}

//Если пользователь зашел с вк задаем ему роль 
if (isset($_SESSION['token'])) {
    $login = $_GET['code'];
    include './connect.php';
    $query = "INSERT INTO `users` (`USER_ID`, `LOGIN`, `PASSWORD`,`User_role`) VALUES (NULL, '$login', '$login','2')";
    $success = $mysqli->query($query);
}

// Если пользователь авторизован
if (isset($_SESSION['token']) || $_SESSION["isauth"]) {
    include './page.php';
?>

<?php

};

?>