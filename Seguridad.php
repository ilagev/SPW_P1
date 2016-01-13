<html>
<head>
<html>
    <head>
        <title> Seguridad </title>
		<meta charset="UTF-8">
    </head>
<body>    
<?php
	session_start();
	include ("/includes/autenticado.php");

	// TODO 5: Comprobar autorización del usuario
        $strbin = decbin($_SESSION['permisos']);
        $bit = $strbin[strlen($strbin) - 6];
        if ($bit != '1') {
            header("Location:NoAuth.php");
        }
        
?>
	<br><br>
    <center>
		<img src="logo.png" width= 120 height= 60>
		<br><br>
		<H2> SEGURIDAD EN LA PROGRAMACIÓN WEB </H2>
		<HR> <BR>
		<a href = '/tema1'> Tema 1: Introducción </a><br><br>
        <a href = '/tema1'> Tema 2: Conceptos previos: HTTP y Apache </a><br><br>
		<a href = '/tema1'> Tema 3: Autenticación y Autorización </a><br><br>
        <a href = '/tema1'> Tema 4: El protocolo TLS/SSL </a><br><br>
		<a href = '/tema1'> Tema 5: Cross Site Scripting </a><br><br>
		<a href = '/tema1'> Tema 6: Robo de Sesiones </a><br><br>
		<a href = '/tema1'> Tema 7: SQL injection </a><br><br>
		<a href = '/tema1'> Tema 8: Otros riesgos en las aplicaciones web </a><br><br>
		<a href = '/tema1'> Tema 9: Análisis de vulnerabilidades en las aplicaciones web </a><br><br><br>
		<a href = 'MasterWeb.php'> VOLVER A MASTER INGENIERIA WEB
	</center>
</body>
</html>