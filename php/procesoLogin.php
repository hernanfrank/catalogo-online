<?php
session_start();

if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
    header("Location: ../index.php");
    die();
}

try {
    $captcha = htmlspecialchars($_POST['captcha']);

    if ($captcha != htmlspecialchars($_SESSION['captcha'])) {
        $_SESSION['flash_message'] = "Codigo incorrecto.";
        header("Location: login.php");
        die();
    }

    $usuario = htmlspecialchars($_POST['usuario']);
    $pass = htmlspecialchars($_POST['pass']);
    $pass = hash('sha512', $pass);

    $mysqli = new mysqli('127.0.0.1', 'root', '', 'tienda');

    $sql = "SELECT clave FROM usuarios WHERE nombre = '$usuario'";
    $result = $mysqli->query($sql);

    if ($result == false || $result == null) {
        $_SESSION['flash_message'] = "Usuario inexistente.";
        $mysqli->close();
        header("Location: login.php");
        die();
    }

    $res = $result->fetch_array(MYSQLI_ASSOC);

    if ($res['clave'] == $pass) {
        $sqlId = "SELECT id FROM usuarios WHERE nombre = '$usuario'";
        $resultId = $mysqli->query($sqlId);
        $id = $resultId->fetch_all(MYSQLI_ASSOC);
        $_SESSION['mensaje_correcto'] = "Ingreso correctamente";
        $_SESSION['usuario'] = $usuario;
        $_SESSION['id'] = $id[0]['id'];
        $mysqli->close();
        header("Location: ../html/inicio.php");
        die();
    } else {
        $_SESSION['flash_message'] = "Contraseña incorrecta.";
        $mysqli->close();
        header("Location: login.php");
        die();

    }
} catch (Exception $e) {
    error_log($e);
    $mysqli->close();
    die();
}