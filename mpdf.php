<!DOCTYPE html>
<html lang="es">
<head>
  <title>Nidix Networks - Referencia Oxxo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/font-awesome.min.css?v=3">
  <link href="css/styles.css" media="all" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">

<body>
<br><br><br>


<?php

require_once __DIR__ . '/vendor/autoload.php';


if(!empty($_POST["idCliente"])){
	$id_cliente = $_POST["idCliente"];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://clientes.nidix.mx/api/v1/GetClientsDetails");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
	  \"token\": \"QUE4L2hHOG1POTUvWURET2FTYXRHZz09\",
	  \"idcliente\": ".$id_cliente.",
	  \"cedula\": \"0\"
	}");

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json"
	));

	$response = curl_exec($ch);
	curl_close($ch);
	$jsonData = json_decode($response, true);

	//Datos a Pantalla
	$estado = $jsonData["estado"]; // [estado] => exito
	//echo $estado;
	if($estado=='error' || $estado=='RETIRADO'){
		echo '<font size=5 color=brown>No se encuentra el número de cliente</font>';
		echo '<meta http-equiv="refresh" content="2;url=index.php">';
	}else
	{
		
		$cli_id =  $jsonData["datos"][0]["id"];
		$cli_nombre =  $jsonData["datos"][0]["nombre"];
		$cli_estado =  $jsonData["datos"][0]["estado"];
		if($cli_estado=='RETIRADO'){
					echo '<font size=5 color=brown>No se encuentra el número de cliente</font>';
					echo '<meta http-equiv="refresh" content="2;url=index.php">';
				}else
				{

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://clientes.nidix.mx/api/v1/GetInvoices");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);

				curl_setopt($ch, CURLOPT_POST, TRUE);

				curl_setopt($ch, CURLOPT_POSTFIELDS, "{
				  \"token\": \"QUE4L2hHOG1POTUvWURET2FTYXRHZz09\",
				  \"limit\": 25,
				  \"estado\": 1,
				  \"idcliente\": ".$id_cliente."
				 }");

				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				  "Content-Type: application/json"
				));

				$response = curl_exec($ch);
				curl_close($ch);
				$jsonData = json_decode($response, true);
				$oxxo = $jsonData["facturas"][0]["oxxo_referencia"];
				$total = $jsonData["facturas"][0]["total"];
				$vence = $jsonData["facturas"][0]["vencimiento"];

			
				 
				  $value = $oxxo;
				  $value = substr($value, 0);
				  $oxxo = ' ' . join(' ', str_split($value, 4));  
				
				  $codigoBarras = "<img class='image-center' src='barcode.php?text={$oxxo}&size=50&orientation=horizontal&print=true'>";
			
				  $print = '';


			  $mpdf = new \Mpdf\Mpdf();


			 
			  $stylesheet = file_get_contents('css/styles.css');
			  
			  $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

			$mpdf->WriteHTML('<div class="opps">
				 
			<div class="opps-header">
				<div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
				<div class="opps-info">
					<div class="opps-brand"><img src="images/oxxopay_brand.png" alt="OXXOPay"></div>
					<div class="opps-ammount">
					<h3>Monto a pagar</h3>
					<h2>$'.$total.'<sup>MXN</sup></h2>
					<b>Estado: '. $cli_estado .'</b><br>
					<b>Vence: '.$vence.'</b><br>
					<b>No.Cuenta: '.$cli_id .'</b>
				   <br>
					
					
					<p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
					</div>
				</div>
				<div class="opps-reference">
					<h3>Referencia</h3>
					<h1>'.$oxxo.'</h1>
					<div >'.$codigoBarras.'</div>
				</div>
			</div>
			<div class="opps-instructions">
				<h3>Instrucciones</h3>
				<ol>
					<li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
					<li>Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
					<li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
					<li>Realiza el pago correspondiente con dinero en efectivo.</li>
					<li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
				</ol>
				<div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>Nombre del negocio</strong> confirmando tu pago.</div>
			</div>
		</div>',\Mpdf\HTMLParserMode::HTML_BODY);
			//$mpdf->Output();	
			$mpdf->Output($oxxo.'-'.$vence.'.pdf',\Mpdf\Output\Destination::DOWNLOAD);		
					

			
				
		}		
	}			
}  


