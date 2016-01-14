<?php
	session_start();
	// TODO 10
	// Comprobar que el CN del certificado es un usuario en la BD.
	$usuario = $_SERVER['SSL_CLIENT_S_DN_CN'];
        include ("includes/abrirbd.php");
        $sql = "SELECT * FROM usuarios WHERE user ='{$usuario}'";
        $resultado = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($resultado);
        $usuario = mysqli_fetch_assoc($resultado);
        if ($num_rows == 0) {
            $_SESSION['autenticado'] = 'incorrecto';
            header("Location: NoAuth.php");
        } else {
            $_SESSION['autenticado'] = 'correcto';
            $_SESSION['permisos'] = $usuario['permisos'];
            header("Location: MasterWeb.php");
        }
        mysqli_close($link);
        exit();
?>
 