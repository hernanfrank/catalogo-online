<?php
session_start();

if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
    header("Location: ../index.php");
    die();
}

try {
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'tienda');
    $mimepermitidos = array("image/png", "image/jpg", "image/jpeg");


    $sqlcategoria = "SELECT id FROM categorias";
    $res = $mysqli->query($sqlcategoria);
    if ($res != null && $res != false)
        $categorias = $res->fetch_all();

    $nombreP = htmlspecialchars($_POST['nombre']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $categoria = intval(filter_var($_POST['idcategoria'], FILTER_VALIDATE_INT));
    $precio = floatval(filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT));
    $cantidad = intval(filter_var($_POST['stock'], FILTER_VALIDATE_INT));
    $idVendedor = $_SESSION['id'];

    //chequea categoria
    $categoriaok = false;
    foreach ($categorias as $c) {
        if ($c[0] == $categoria) {
            $categoriaok = true;
        }
    }
    if (!$categoriaok) {
        $_SESSION['flash_message'] = "Seleccione una categoria permitida";
        header("Location: form_producto.php");
        die();
    }

    if ($cantidad < 1) {
        $_SESSION['flash_message'] = "No se permite cantidad menor a 1";
        header("Location: form_producto.php");
        die();
    }

    if ($precio < 0) {
        $_SESSION['flash_message'] = "El precio no puede ser negativo";
        header("Location: form_producto.php");
        die();
    }

    //si hay imagen cargada la chequea
    if (isset($_FILES['imagen'])) {
        //carga imagen
        $target_dir = getcwd() . "/../img/uploads/";
        $uploadOk = true;
        $target_file = $target_dir . basename($_FILES["imagen"]["tmp_name"]);
        $imgpath = "/tp/img/uploads/" . basename($_FILES["imagen"]["tmp_name"]);

        // Check if image file is a actual image or fake image
        if (isset($_POST["imagen"])) {
            $check = getimagesize($_FILES["imagen"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = true;
            } else {
                $uploadOk = false;
            }
        }

        // error si no es una imagen
        if (!$uploadOk || !in_array(mime_content_type($_FILES["imagen"]["tmp_name"]), $mimepermitidos)) {
            $_SESSION['flash_message'] = "Imagen no soportada";
            header("Location: form_producto.php");
            die();
        }

        // Limita tamaño de imagen
        if ($_FILES["imagen"]["size"] > 5000000) {
            $_SESSION['flash_message'] = "El tamaño de la imagen debe ser menor a 5mb";
            ("Location: form_producto.php");
            die();
        }

        //si no falla nada, subo imagen
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            } else {
                //error si falla la carga de imagen
                $_SESSION['flash_message'] = "Ha ocurrido un error en la carga de la imagen";
                header("Location: form_producto.php");
                die();
            }

        }
    }

    $sql = "INSERT INTO productos(nombre,descripcion,idcategoria,precio,stock,imgpath,idvendedor) VALUES ('$nombreP','$descripcion','$categoria',$precio,$cantidad,'$imgpath',$idVendedor)";

    $mysqli->query($sql);
    $mysqli->close();

    $_SESSION['flash_message_carga'] = "Carga exitosa";
    header("Location: ../html/inicio.php");
    die();
} catch (Exception $e) {
    error_log($e);
    echo "Ocurrió un error";
    die();
}