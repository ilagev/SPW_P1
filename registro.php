<html>
	<head>
		<title> Registro </title>
		<meta charset="UTF-8">
	</head>
<body>
<?php
	if (isset($_POST['registro'])){
        include ("includes/abrirbd.php");
        $sql = "SELECT * FROM usuarios WHERE user ='{$_POST['user']}'";
        $resultado = mysqli_query($link, $sql);
        if (mysqli_num_rows ($resultado) != 0){
             echo "<Center> <font color= red>";
			 echo "<BR><BR><BR>Usuario ya existente <BR><BR>";
             echo "<A href= '{$_SERVER['PHP_SELF']}'> Volver a registro </A>";
             echo "</Center></font>"; 
	   } else {
			$password = $_POST['passwd'];
			$salt = microtime();
			$hash = hash("sha256", $password . $salt, false);
           	$sql = "INSERT INTO usuarios (user, password, salt, nombre, apellidos, permisos) VALUES ('{$_POST['user']}', '{$hash}', '{$salt}','{$_POST['nombre']}', '{$_POST['apellidos']}',0)";
            $resultado = mysqli_query ($link, $sql);
            if (!$resultado) {
				echo "</Center></font>"; 
				echo ("<BR><BR><BR>Datos erróneos".mysqli_error($link)."<BR><BR>");
				echo "<A href= '{$_SERVER['PHP_SELF']}'> Volver a registro </A>";
				echo "</Center></font>"; 
		    } else {
				echo "<BR><BR><BR><CENTER>";
				echo "Usuario Registrado <BR><BR>";
				echo "<A href= 'login.php'> Volver a login </A>";
				echo "</CENTER>";
			}
       }
	   mysqli_close($link);
	} else {
?>
		<br><br><br>
		<center>
			<img src="logo.png" width= 120 height= 60>
			<br>
			<h2> REGISTRO DE UN NUEVO USUARIO </h2>
			<br>
			<form action= '<?php "{$_SERVER['PHP_SELF']}"  ?>' method = post>
				<table bgcolor = 'lightgrey'> 
					<tr>
						<td width= 100> Usuario: </td> 
						<td> <input type = text name ='user'></td>
					</tr>
					<tr>
						<td width= 100> Password: </td> 
						<td> <input type = password name ='passwd'></td>
					</tr>
					<tr>
						<td width= 100> Nombre: </td> 
						<td> <input type = text name = 'nombre'></td>
					</tr>
					<tr>
						<td width= 100> Apellidos: </td> 
						<td> <input type = text name = 'apellidos'></td>
					</tr>
				</table><br>
				<input type=submit name = 'registro' value = "REGISTRO">
			</form>
			<br><br><A href= 'login.php'> Volver a login </A>
		</center>
<?php
	}
?>
</body>
</html>