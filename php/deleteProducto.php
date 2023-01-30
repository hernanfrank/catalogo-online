<?php
session_start();


if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
    header("Location: ../index.php");
    die();
}

try {


    $id = filter_var($_POST['productoID'], FILTER_VALIDATE_INT);

    $mysqli = new mysqli('127.0.0.1', 'root', '', 'tienda');

    $sql = "DELETE FROM productos WHERE id=$id";

    $mysqli->query($sql);

    $mysqli->close();

    // echo "El nuevo registro se guardo con id: ".$mysqli->insert_id;
    $_SESSION['flash_message_eliminacion'] = "Eliminacion exitosa";
    header("Location: ../html/inicio.php");
} catch (Exception $e) {
    echo "Ha ocurrido un error.";
    error_log($e);
    die();
}
