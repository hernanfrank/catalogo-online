<?php
session_start();

if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
    header("Location: ../index.php");
    die();
}

try {

    $mysqli = new mysqli('127.0.0.1', 'root', '', 'tienda');
    $mimepermitidos = array("image/png", "image/jpg", "image/jpeg");

    $id = intval($_POST['productoID']);

    $sqlcategoria = "SELECT id FROM categorias";
    $res = $mysqli->query($sqlcategoria);
    if ($res != null && $res != false)
        $categorias = $res->fetch_all();

    if (isset($_POST['nombre'])) {
        $nombreP = htmlspecialchars($_POST['nombre']);
    } else {
        $_SESSION['flash_message'] = 'Error en el parametro nombre';
        header("Location: ../php/form_producto.php?productoID=" . $_POST['productoID']);
        die();
    }
    if (isset($_POST['descripcion'])) {
        $descripcion = htmlspecialchars($_POST['descripcion']);
    } else {
        $_SESSION['flash_message'] = 'Error en el parametro descripcion';
        header("Location: ../php/form_producto.php?productoID=" . $_POST['productoID']);
        die();
    }
    if (isset($_POST['idcategoria'])) {
        $categoria = filter_var($_POST['idcategoria'], FILTER_VALIDATE_INT);
    } else {
        $_SESSION['flash_message'] = 'Error en el parametro categoria';
        header("Location: ../php/form_producto.php?productoID=" . $_POST['productoID']);
        die();
    }
    if (isset($_POST['precio'])) {
        if (intval($_POST['precio']) > 0) {
            $precio = floatval(filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT));
        } else {
            $_SESSION['flash_message'] = 'El precio no puede ser negativo';
            header("Location: ../php/form_producto.php?productoID=" . $_POST['productoID']);
            die();
        }
    } else {
        $_SESSION['flash_message'] = 'Error en el parametro precio';
        header("Location: ../php/form_producto.php?productoID=" . $_POST['productoID']);
        die();
    }
    if (isset($_POST['stock'])) {
        $cantidad = intval(filter_var($_POST['stock'], FILTER_VALIDATE_INT));
    } else {
        $_SESSION['flash_message'] = 'Error en el parametro stock';
        header("Location: ../php/form_producto.php?productoID=" . $_POST['productoID']);
        die();
    }

    $sql = "SELECT nombre, descripcion, idcategoria, stock, precio, imgpath FROM productos WHERE idvendedor = " . $_SESSION['id'] . " AND id = " . $id;
    $result = $mysqli->query($sql);

    if ($result != false && $result != null)
        $producto = $result->fetch_array(MYSQLI_ASSOC);
    else {
        $_SESSION['flash_message'] = 'Ha ocurrido un error, intente nuevamente';
        header("Location: ../php/form_producto.php?productoID=" . $_POST['productoID']);
        die();
    }

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

    //preparo el sql a ejecutar
    $actualiza = false;
    $sqlU = "UPDATE productos SET";

    //actualizo solo los campos que cambiaron
    if (isset($nombreP) && $nombreP != $producto['nombre']) {
        $sqlU .= " nombre='$nombreP' ,";
        $actualiza = true;
    }
    if (isset($descripcion) && $descripcion != $producto['descripcion']) {
        $sqlU .= " descripcion='$descripcion' ,";
        $actualiza = true;
    }
    if (isset($categoria) && $categoria != $producto['idcategoria']) {
        $sqlU .= " idcategoria='$categoria' ,";
        $actualiza = true;
    }
    if (isset($cantidad) && $cantidad != $producto['stock']) {
        $sqlU .= " stock='$cantidad' ,";
        $actualiza = true;
    }
    if (isset($precio) && $precio != $producto['precio']) {
        $sqlU .= " precio='$precio' ,";
        $actualiza = true;
    }

    //si hay imagen cargada la chequea
    if (isset($_FILES['imagen'])) {
        //carga imagen
        $target_dir = getcwd() . "/../img/uploads/";
        $uploadOk = true;
        $target_file = $target_dir . basename($_FILES["imagen"]["tmp_name"]);
        $imgpath = "/tp/img/uploads/" . basename($_FILES["imagen"]["tmp_name"]);

        //chequea si no es la misma imagen
        if ($imgpath != $producto['imgpath'] && $_FILES["imagen"]["tmp_name"] != "") {

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
                    $sqlU .= " imgpath = '$imgpath' ,";
                    $actualiza = true;
                } else {
                    //error si falla la carga de imagen
                    $_SESSION['flash_message'] = "Ha ocurrido un error en la carga de la imagen";
                    header("Location: form_producto.php");
                    die();
                }

            }
        }

    }

    //selecciono el producto por su id
    $sqlU .= " id = $id WHERE id = $id";

    //si hay cambios para hacer, los hago
    if ($actualiza) {
        error_log($sqlU);
        $mysqli->query($sqlU);
        $_SESSION['flash_message_edicion'] = "Edicion exitosa";
    } else {
        $_SESSION['flash_message_edicion'] = "No se registraron cambios";
    }
    $mysqli->close();

    header("Location: ../html/inicio.php");
} catch (Exception $e) {
    error_log($e);
    echo "Ha ocurrido un error.";
    die();
}