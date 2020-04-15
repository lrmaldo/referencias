<!DOCTYPE html>
<html lang="es">
<head>
  <title>Nidix Networks - Referencia Oxxo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/font-awesome.min.css?v=3">
  <link href="css/styles.css" media="all" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
</head>
<body>
<br><br><br>


<?php




if(!empty($_POST["idCliente"])){
	$id_cliente = $_POST["idCliente"];
	////quita ceros -------------------

//busca los dos primeros ceros
$posicion_coincidencia = strpos($id_cliente, '00');

$id_cliente_formateado =''; ///esta variable se usa para guardar el idcliente dependiendo de la condicion
///se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
if ($posicion_coincidencia === false) {
    //si al inicio no hay ceros devuelve false
    $id_cliente_formateado = $id_cliente; // se guarda el id en esta variable  sino cumple la condicion
    } else {
            //si hay devolvera la posicion de esos 0 
            
            // se quita los dos ceros  y se pasa a una variable  nueva para hacer la consulta al API

            $id_cliente_formateado = substr($id_cliente, 2);
             
            }


	///---------------------
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://clientes.nidix.mx/api/v1/GetClientsDetails");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
	  \"token\": \"QUE4L2hHOG1POTUvWURET2FTYXRHZz09\",
	  \"idcliente\": ".$id_cliente_formateado.",
	  \"cedula\": \"0\"
	}");

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json"
	));

	$response = curl_exec($ch);
	curl_close($ch);
	$jsonData = json_decode($response, true);
	//echo $response;
	//Datos a Pantalla
	$estado = $jsonData["estado"]; // [estado] => exito
	//echo $estado;
	if($estado=='error' || $estado=='RETIRADO'){
		echo '<section class="container ">
			<div class="row justify-content-center">
				<div class="col-lg-12" id="resultsDiv">
				<font class="mb-4 align-self-center" size=30 color=brown>No se encuentra el número de cliente.</font>
					</div>
					</div>
				  </section>';
		echo '<meta http-equiv="refresh" content="2;url=index.php">';
	}else
	{
		
		$cli_id =  $jsonData["datos"][0]["id"];
		$cli_nombre =  $jsonData["datos"][0]["nombre"];
		$cli_estado =  $jsonData["datos"][0]["estado"];
		if($cli_estado=='RETIRADO'){
			echo '<section class="container ">
			<div class="row justify-content-center">
				<div class="col-lg-12" id="resultsDiv">
				<font class="mb-4 align-self-center" size=30 color=brown>No se encuentra el número de cliente.</font>
					</div>
					</div>
				  </section>';
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
				  \"idcliente\": ".$id_cliente_formateado."
				 }");

				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				  "Content-Type: application/json"
				));

				$response = curl_exec($ch);
				curl_close($ch);
				//echo $response;
				$jsonData = json_decode($response, true);
				//echo $response;
				//Datos a Pantalla
				$estado = $jsonData["estado"]; // [estado] => exito
				
				if($estado=='error'){

					echo '<section class="container ">
					<div class="row justify-content-center">
						<div class="col-lg-12" id="resultsDiv">
						<font class="mb-4 align-self-center" size=30 color=brown>No existe ninguna factura próximo a vencer.</font>
							</div>
							</div>
			 			 </section>';
				
				echo '<meta http-equiv="refresh" content="3;url=index.php">';
				}else
					{
				
				//$jsonData = json_decode($response, true);
				$oxxo = $jsonData["facturas"][0]["oxxo_referencia"];
				$total = $jsonData["facturas"][0]["total"];
				$vence = $jsonData["facturas"][0]["vencimiento"];

			
				 
				  $value = $oxxo;
				  $value = substr($value, 0);
				  $oxxo = ' ' . join(' ', str_split($value, 4));  
				
				  $codigoBarras = "<img class='image-center' src='barcode.php?text={$oxxo}&size=50&orientation=horizontal&print=true'>";
				echo '<div class="container">
				<div class="row">
				  <div class="col-sm">
				  </div>
				  <div class="col-sm">
				  </div>
				  <div class="col-sm">
				  <form action="mpdf.php" method="POST">
				  <input type="hidden" id="idCliente" name="idCliente" value="'.$id_cliente_formateado.'">
				  <button class="btn btn-primary"  type="submit" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Guardar</button>
				  </form>
				  </div>
				</div>
			  </div>';
				  $print = '<div class="opps">
				 
				  <div class="opps-header">
					  <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
					  <div class="opps-info">
						  <div class="opps-brand"><img src="images/oxxopay_brand.png" alt="OXXOPay"></div>
						  <div class="opps-ammount">
							  <h4>Monto a pagar</h4>
							  <h2>$'.$total.'<sup>MXN</sup></h2>
							  <font size=2 color="black"><b>Estado: '. $cli_estado .'</b></font><br>
							  <font size=2 color="black"><b>Vence: '.$vence.'</b></font><br>
							 
							  <font size=2>No.Cuenta:</font>  <font size=2 color="blue"><b>'.$cli_id .'</b></font></p></font>
							  <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
						  </div>
					  </div>
					  <div class="opps-reference">
						  <h3>Referencia</h3>
						  <h1>'.$oxxo.'</h1>
					  </div>
					  
				  </div>
				  <div >'.$codigoBarras.'</div>
				  <div class="opps-instructions">
					  <h3>Instrucciones</h3>
					  <ol>
						  <li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
						  <li>Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
						  <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
						  <li>Realiza el pago correspondiente con dinero en efectivo.</li>
						  <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
					  </ol>
					  <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>Nidix Networks</strong> confirmando tu pago.</div>
				  </div>
			  </div>	';

					echo $print;
					

			
					}	
		}		
	}			
}  


