<?php


function ResetPassword($link){
	$html ='
	<!DOCTYPE html>
	<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="x-apple-disable-message-reformatting">
		<title></title>
		<style>
			table, td, div, h1, p {font-family: Arial, sans-serif;}
		</style>
	</head>
	<body style="margin:0;padding:0;">
		<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
			<tr>
				<td align="center" style="padding:0;">
					<table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
						<tr>
							<td align="center" style="padding:40px 0 30px 0;background:#e0e0e0;">
								<img src="https://ivangladesh.000webhostapp.com/assets/proarp-min.png" alt="" width="300" style="height:auto;display:block;" />
							</td>
						</tr>
						<tr>
							<td style="padding:36px 30px 42px 30px;">
								<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
									<tr>
										<td style="padding:0 0 0 0;color:#153643;">
											<h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Reestablecer contraseña</h1>
											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Siga el siguiente enlace para reestablecer su contraseña, la vigencia del mensaje es de 24 a partir de que recibe este correo electrónico.</p>
											<p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href='. $link .' style="color:#ee4c50;text-decoration:underline;">Reestablecer contraseña</a></p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding:30px;background:#eeeeee;">
								<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
									<tr>
										<td style="padding:0;width:50%;" align="left">
											<p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#757575;">
												&reg; Proarp 2022<br/>
											</p>
										</td>
										<td style="padding:0;width:50%;" align="right">
											<table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
												<tr>
													<td style="padding:0 0 0 10px;width:38px;">
														<a href="https://ivangladesh.000webhostapp.com/" style="color:#757575;"><img src="https://ivangladesh.000webhostapp.com/assets/proarp-min.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
	</html>';
	return $html;
}

function ValidarCuenta($link){
	$html ='
	<!DOCTYPE html>
	<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="x-apple-disable-message-reformatting">
		<title></title>
		<style>
			table, td, div, h1, p {font-family: Arial, sans-serif;}
		</style>
	</head>
	<body style="margin:0;padding:0;">
		<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
			<tr>
				<td align="center" style="padding:0;">
					<table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
						<tr>
							<td align="center" style="padding:40px 0 30px 0;background:#e0e0e0;">
								<img src="https://ivangladesh.000webhostapp.com/assets/proarp-min.png" alt="" width="300" style="height:auto;display:block;" />
							</td>
						</tr>
						<tr>
							<td style="padding:36px 30px 42px 30px;">
								<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
									<tr>
										<td style="padding:0 0 0 0;color:#153643;">
											<h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Validar cuenta</h1>
											<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Siga el siguiente enlace para activar su cuenta, la vigencia del mensaje es de 24 a partir de que recibe este correo electrónico.</p>
											<p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href='. $link .' style="color:#ee4c50;text-decoration:underline;">Validar cuenta</a></p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding:30px;background:#eeeeee;">
								<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
									<tr>
										<td style="padding:0;width:50%;" align="left">
											<p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#757575;">
												&reg; Proarp 2022<br/>
											</p>
										</td>
										<td style="padding:0;width:50%;" align="right">
											<table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
												<tr>
													<td style="padding:0 0 0 10px;width:38px;">
														<a href="https://ivangladesh.000webhostapp.com/" style="color:#757575;"><img src="https://ivangladesh.000webhostapp.com/assets/proarp-min.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
	</html>';
	return $html;
}

?>