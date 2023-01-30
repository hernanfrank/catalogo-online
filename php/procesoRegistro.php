<?php
session_start();

if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
    header("Location: ../index.php");
    die();
}

try {
    if (isset($_SESSION['usuariorep']))
        unset($_SESSION['usuariorep']);
    if (isset($_SESSION['flash_message']))
        unset($_SESSION['flash_message']);

    //valido usuario
    if ($_POST['user'] != "") {
        $usuario = htmlspecialchars($_POST['user']);
    } else {
        die();
    }

    //valido mail
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        error_log("email");
        $_SESSION['flash_message'] = "Email invalido.";
        header("Location: registro.php");
        die();
    } else {
        $email = htmlspecialchars($_POST['email']);
    }

    //valido password

    $passregex = "/^   
    (?=(?:.*[A-Z]){1,})
    (?=(?:.*[a-z]){1,})
    (?=(?:.*\d){1,})  
    (.{8,})           
    $
    /mx";

    if ($_POST['password'] != "" && preg_match($passregex, $_POST['password'])) {
        $pass = hash('sha512', $_POST['password']);
    } else {
        $_SESSION['flash_message'] = "Contraseña invalida.";
        header("Location: registro.php");
        die();
    }

    if ($_POST['password2'] != "" && preg_match($passregex, $_POST['password2'])) {
        $pass2 = hash('sha512', $_POST['password2']);
    } else {
        $_SESSION['flash_message'] = "Contraseña invalida.";
        header("Location: registro.php");
        die();
    }
    if ($_POST['captcha'] != "") {
        $captcha = htmlspecialchars($_POST['captcha']);
        if ($captcha != $_SESSION['captcha'])
            ;
    } else {
        die();
    }

    $mysqli = new mysqli('127.0.0.1', 'root', '', 'tienda');

    $sql = "SELECT nombre FROM usuarios WHERE nombre = '$usuario'";

    $result = $mysqli->query($sql);

    if ($result != false && $result != null) {
        $res = $result->fetch_array(MYSQLI_ASSOC);
        if ($res != null && $res['nombre'] == $usuario) {
            $_SESSION['flash_message'] = "El nombre de usuario ya existe.";
            $_SESSION['usuariorep'] = $usuario;
            header("Location: registro.php");
            die();
        }
    }


    $sql = "INSERT INTO usuarios(nombre, clave, email) VALUES ('$usuario','$pass', '$email')";

    $mysqli->query($sql);

    $mysqli->close();

    $_SESSION['flash_message'] = "Usuario creado correctamente.";
    $_SESSION['status'] = 'ok';
    $_SESSION['usuariorep'] = $usuario;

    header("Location: ../php/login.php");
} catch (Exception $e) {
    error_log($e);
    die();
}