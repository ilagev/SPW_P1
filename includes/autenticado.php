<?php
    // TODO 4 Comprobar si el usuario está autenticado 
    if (!($_SESSION['autenticado'] == 'correcto')) {
        header("Location:login.php");
        exit();
    }
?>