<?php 
session_start();
if($_POST["token"] == $_SESSION["CSRF"])
{
// Начинаем проверку логина и пароля в БД
    if((isset($_POST["login"]))&& (isset($_POST["pass"])))
    {
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASSWORD', '');
        define('DB_NAME', 'task-27');
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($mysqli->connect_errno) exit('Ошибка соединения с БД');
        $mysqli->set_charset('utf8');

        $password=md5($_POST["pass"]);
        $password=md5($password."MEGASECRET");

        $query="SELECT * FROM users WHERE LOGIN='". $_POST["login"]. "' AND PASSWORD='".  $password. "'";
        $success = $mysqli->query($query);
        if(mysqli_num_rows($success) >0)
        {
            // логин и пароль нашли
            $_SESSION["isauth"] = true;
            echo 'Вы вошли';
            header('location:http://task-27/index.php');
        }
        else
        {   $_SESSION["isauth"] = false;

            header('location:http://task-27/index.php');
            echo "Отображаем сообщение, что логин и пароль не найдены";
        }

    }
}


?>