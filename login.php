<html>
	<head>
		<title> Login </title>
		<meta charset="UTF-8">
	</head>
	<body>

		<?php
		session_start();
		if (isset($_POST['registro'])) {
			header("Location: registro.php");
		}

		if (isset($_POST['login'])) {
                    // TODO 6: Comprobar captcha
                    if ($_SESSION['CAPTCHA'] == $_POST['valor']) {
                        
			include ("includes/abrirbd.php");
			$sql = "SELECT * FROM usuarios WHERE user ='{$_POST['user']}'";
                        //echo "<script>alert(" . $_POST['user'] .")</script>";
			$resultado = mysqli_query($link, $sql);

			if (mysqli_num_rows($resultado) == 1) {
				$usuario = mysqli_fetch_assoc($resultado);
				// TODO 3 Comprobar el password de entrada con el de la BD
                                $passwd_db = hash("sha256", $_POST['passwd'] . $usuario['salt'], false);
                                if ($usuario['password'] == $passwd_db) {
                                        // TODO 3 La condición del if es que el password sea correcto 	
                                        $_SESSION['autenticado'] = 'correcto';
                                        $_SESSION['permisos'] = $usuario['permisos'];
                                        header("Location:MasterWeb.php");
                                } else {
                                        $_SESSION['autenticado'] = 'incorrecto';
                                        header("Location: NoAuth.php");
                                }
			} else {
				$_SESSION['autenticado'] = 'incorrecto';
				header("Location: NoAuth.php");
			}
                    } else {
                        header("Location:login.php");
                    }

			mysqli_close($link);
		} else {
			?>
			<br><br><br>
		<center>
			<img src="logo.png" width= 120 height= 60>
			<br><br><br>
			<form action= '<?php "{$_SERVER['PHP_SELF']}" ?>' method = post>
				<input type=submit name = 'registro' value = "REGISTRAR USUARIO"> <br><br><br>
				<table bgcolor = 'lightgrey'> 
					<tr>
						<td width= 100> Usuario: </td> 
						<td> <input type = text name ='user'></td>
					</tr>
					<tr>
						<td width= 100> Password: </td> 
						<td> <input type = password name ='passwd'></td>
					</tr>
				</table><br>
                                <img src= captcha.php>
                                <input type= text name= 'valor'><br><br>
				<input type=submit name = 'login' value = "LOGIN"><br>
			</form>
			<?php
		}
		?>
	</center>
</body>
</html>