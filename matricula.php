<HTML>
	<HEAD>
		<title>Matrícula de asignaturas</title>
		<meta charset="UTF-8">
	</HEAD>
	<body>

		<?php
		session_start();
		include ("/includes/autenticado.php");
		
		if (isset($_POST['Envio'])) {

			// GUARDAR EN UN FICHERO LA FIRMA JUNTO CON EL CERTIFICADO (FORMATO PKCS7)
			$numero = uniqid();
			$nombreFirma = "firmas/firma_" . $numero . ".pem";
			$fp = fopen($nombreFirma, "w");
			fwrite($fp, "-----BEGIN PKCS7-----\n");
			fwrite($fp, $_POST['firma']);
			fwrite($fp, "\n-----END PKCS7-----");
			fclose($fp);

			// GUARDAR EN UN FICHERO EL MENSAJE RECIBIDO EN EL FORMULARIO
			$texto = "";
			if (isset($_POST['SPW']))
				$texto = $texto . "SPW=" . $_POST['SPW'];
			if (isset($_POST['DAWTP']))
				$texto = $texto . "DAWTP=" . $_POST['DAWTP'];
			if (isset($_POST['DAWDCA']))
				$texto = $texto . "DAWDCA=" . $_POST['DAWDCA'];

			$nombreTexto = "firmas/texto_" . $numero . ".txt";
			$ft = fopen($nombreTexto, "w");
			fwrite($ft, $texto);
			fclose($ft);

			// INVOCAR AL COMANDO smime DE OpenSSL PARA COMPROBAR FIRMA
			$comando = "c:/xampp/apache/bin/openssl smime -verify -in $nombreFirma -inform PEM";
			$comando.= " -binary -content $nombreTexto -CAfile c:/xampp/apache/conf/ssl.crt/CAMIW.crt";
			exec($comando . " 2>&1", $salida, $res);

			//COMPROBAR RESULTADO 
			if ($res != 0) {
				echo "<CENTER> <H2><FONT color=red> COMPROBACIÓN DE FIRMA INCORRECTA";
			} else {
				echo "<CENTER> <H2>FIRMA CORRECTA</H2><BR>";
				// TODO 12: comprobar que el usuario firmante coincide con el usuario autenticado	

				// TODO 11: matricular al usuario en las asignaturas seleccionadas en el formulario
                                $user = $_SERVER['SSL_CLIENT_S_DN_CN'];
                                include ("includes/abrirbd.php");
                                $sql = "SELECT * FROM usuarios WHERE user ='{$user}'";
                                $resultado = mysqli_query($link, $sql);
                                $num_rows = mysqli_num_rows($resultado);
                                if ($num_rows == 1) {
                                    $usuario = mysqli_fetch_assoc($resultado);
                                    $strbin = decbin($usuario['permisos']);
                                    $strbin = "000000000000000" . $strbin;
                                    if (isset($_POST['SPW'])) {
                                        $strbin[strlen($strbin) - 6] = '1';
                                    }
                                    if (isset($_POST['DAWTP'])) {
                                        $strbin[strlen($strbin) - 5] = '1';
                                    }
                                    if (isset($_POST['DAWDCA'])) {
                                        $strbin[strlen($strbin) - 4] = '1';
                                    }
                                    $intbin = bindec($strbin);
                                    $update_sql = "UPDATE usuarios SET permisos='{$intbin}'WHERE user='{$user}'";
                                    if (mysqli_query($link, $update_sql)) {
                                        echo "Record updated successfully";
                                    } else {
                                        echo "Error updating record: " . mysqli_error($link);
                                    }
                                    mysqli_close($link);
                                    $_SESSION['permisos'] = $intbin;
                                } else {
                                    echo "<CENTER> <H2><FONT color=red> HA OCURRIDO UN ERROR";
                                }
			}
			?>
			<br><br><A href= 'MasterWeb.php'> Volver a inicio </A>

	<?php
} else {
	?>
			<SCRIPT type="text/JavaScript">
				function firmarFormulario(){
				var texto="";
				if (matricula.SPW.checked) texto = texto + "SPW=" + matricula.SPW.value;
				if (matricula.DAWTP.checked) texto = texto + "DAWTP=" + matricula.DAWTP.value;
				if (matricula.DAWDCA.checked) texto = texto + "DAWDCA=" + matricula.DAWDCA.value;
				firma = window.crypto.signText (texto, "ask");
				matricula.firma.value = firma;
				return true;
				}
			</SCRIPT>
		<center>
			<img src="logo.png" width= 120 height= 60>
			<br><br><br>
			<H2> Selecciona las asignaturas en que quieres matricularte: </H2><BR><BR>
			<FORM name="matricula" method=post action=matricula.php onSubmit="firmarFormulario()">
				<TABLE>
					<TR>
						<TD align=right><INPUT type="checkbox" name="SPW" value="Si"></TD>
						<TD align=left> Seguridad en la Programación Web</TD>
					</TR>
					<TR>
						<TD align=right><INPUT type="checkbox" name="DAWTP" value="Si"></TD>
						<TD align=left> Desarrollo de Aplicaciones Web con Tecnologías Propietarias</TD>
					</TR>
					<TR>
						<TD align=right><INPUT type="checkbox" name="DAWDCA" value="Si"></TD>
						<TD align=left> Desarrollo de Aplicaciones Web Distribuidas de Código Abierto</TD>
					</TR>
				</TABLE><BR>
				<INPUT type="hidden" name="firma">
				<INPUT type="submit" name="Envio" value="Firmar y Enviar">
			</FORM>
		</CENTER>
	<?php
}
?>
</BODY>
</HTML>

