<?php



function getInformacionCliente($apiData, $idCliente)
{
	$urlapi   = 'http://192.168.4.249/admin/api.php';
	$postfields = [
		'userapi'     => $apiData['userapi'],
		'passwordapi' => $apiData['passwordapi'],
		'comando'     => 'RevisarCliente',
		'idcliente'   => $idCliente
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$urlapi);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
	$curlResponse = curl_exec($ch);
	if (curl_error($ch)) {
	    die('No puede conectarse:a ' . curl_errno($ch) . ' - ' . curl_error($ch));
	}
	curl_close($ch);
	return json_decode($curlResponse, true);
}

function getData($apiData, $idCliente)
{
	$urlapi = 'http://192.168.4.249/admin/api.php';
	$postfields = [
		'userapi'     => $apiData['userapi'],
		'passwordapi' => $apiData['passwordapi'],
		'comando'     => 'RevisarFactura',
		'nfactura'    => '', //--> ID de Factura (Dejar vacío si vamos a buscar por Id cliente).
		'idcliente'   => $_POST['id'] //--> ID del clientes si deseamos mostrar su última Factura (Ejm: 000001)
	];
	//-->LLamar a la API
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$urlapi);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
	$curlResponse = curl_exec($ch);
	if (curl_error($ch)) {
	    die('No puede conectarse:a ' . curl_errno($ch) . ' - ' . curl_error($ch));
	}
	curl_close($ch);
	return json_decode($curlResponse, true);
}

function generaContenido($informacionCliente, $data)
{
	if (!empty($data['items'])) {
		$items .= '<ul>';
		foreach ($data['items'] as $item) {
			$items .= "<li>{$item['descripcion']} <span>{$item['monto']}</span></li>";
		}
		$items .= '</ul>';
		
		$codigoBarras = "<img src='barcode.php?text={$data['referencia_oxxopay']}&size=50&orientation=horizontal&print=true'>";
	$items   = '';
	$content = '';
	}
	$response['status'] = 'success';
	
	$content .= '<table class="table table-striped table-responsive"><tbody>';
	$content .= '<tr>';
	$content .= "<td><b>Nombre:</b></td><td><h2>{$informacionCliente['nombre']}</h2></td>";
	$content .= '</tr><tr>';
	$content .= "<td><b>Referencia OXXO:</b></td><td>{$codigoBarras}</td>";
	$content .= '</tr>';
	$content .= '</tbody></table>';
	

	
	
	$content .= '<table class="table table-striped"><tbody>';
	//$content .= '<tr>
    //		<td colspan=2 class="text-right">
    // 			<button class="btn btn-sm btn-info"><i class="fa fa-print"></i> Imprimir</button>
    // 		</td>
	//		</tr>';
	$content .= '<tr>';
	$content .= "<td><b>Id:</b></td><td>{$informacionCliente['id']}</td>";
	$content .= '</tr><tr>';
	$content .= "<td><b>Email:</b></td><td>{$informacionCliente['correo']}</td>";
	$content .= '</tr><tr>';
	$content .= "<td><b>No. Factura</b></td><td><h3>{$data['nfactura']}</h3></td>";
	$content .= '</tr>';
	$content .= '</tbody></table>';

	$content .= '<table class="table table-striped table-responsive"><thead>';
	$content .= '<tr>';
		
	$content .= '<th>Fecha de Emisión</th>';
	$content .= '<th>Fecha Vencimiento</th>';
	$content .= '<th>Fecha de Pago</th>';
	
	$content .= '<th>Referencia</th>';
	$content .= '<th>Total</th>';
	$content .= '<th>Estado</th>';
	$content .= '</tr>';
	$content .= '</thead><tbody>';
	$content .= '<tr>';
	$content .= "<td>{$data['emitido']}</td>";
	$content .= "<td>{$data['vencimiento']}</td>";
	$content .= "<td>{$data['fechapagado']}</td>";
	$content .= "<td><b>{$data['referencia_oxxopay']}</b></td>";
	$content .= "<td>{$data['montocobrado']}</td>";
	$content .= "<td>{$data['estado']}</td>";
	$content .= '</tr>';
	$content .= '</tbody><table>';
	$content = "
		<div class='row'>
			<div class='col-lg-2 col-md-1'></div>
			<div class='col-lg-8 col-md-10 col-sm-12 col-xs-12 ticket_oxxo_container'>
				<div class='row'>
					<div class='col-lg-2 col-md-1'></div>
					<div class='col-lg-8 col-md-10 col-sm-12 col-xs-12 text-center ticket_oxxo_topbar'>
							FICHA DIGITAL. NO ES NECESARIO IMPRIMIR
					</div>
				</div>
				<div class='row'>
					<div class='col-lg-2 col-md-1'></div>
					<div class='row col-lg-8 col-md-10 col-sm-12 col-xs-12'>
						<div class='col-lg-4 col-md-4 col-sm-4 col-xs-12 ticket_oxxo_logo'>
							<img src='images/logo-oxxo.png' class='img-responsive'>
						</div>
						<div class='col-lg-8 col-md-8 col-sm-8 col-xs-12 ticket_oxxo_topinfo'>
							<div class='monto-a-pagar-texto'>MONTO A PAGAR</div>
							<div class='monto-a-pagar-cantidad'>$ {$data['montocobrado']} <span class='moneda'>MXN</span></div>
							<div class='monto-a-pagar-leyenda'>OXXO cobrará una comisión adicional al momento de realizar el pago.</div>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-lg-2 col-md-1'></div>
					<div class='col-lg-8 col-md-10 col-sm-12 col-xs-12 ticket_oxxo_data'>
						<p><b>Nombre:</b> {$informacionCliente['nombre']}</p>
						<p><b>Vencimiento:</b> {$data['vencimiento']}</p>
					</div>
				</div>
				<div class='row'>
					<div class='col-lg-2 col-md-1'></div>
					<div class='row col-lg-8 col-md-10 col-sm-12 col-xs-12'>
						<div class='monto-a-pagar-texto'>REFERENCIA</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-lg-2 col-md-1'></div>
					<div class='row col-lg-8 col-md-10 col-sm-12 col-xs-12'>
						<div class='ticket_oxxo_referencia'>{$data['referencia_oxxopay']}</div>
					</div>
				</div>
				<div class='row ticket_oxxo_instrucciones'>
					<span>INSTRUCCIONES</span>
					<ol>
						<li>Acude a la tienda OXXO más cercana.</li>
						<li>Indica en caja que quieres realizar un pago de <b>OXXOPay</b>.</li>
						<li>Dicta al cajero en número de referencia en esta ficha para que tecleé directamente en la pantalla de venta.</li>
						<li>Realiza el pago correspondiente con dinero en efectivo.</li>
						<li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <b>En el podrás verificar que se haya realizado correctamente</b>.  conserva este comprobante de pago.</li>
					</ol>
					<div class='aviso'>Al completar estos pasos recibirás un correo confirmando tu pago.<div>
				</div>
			</div>
		</div>
	";
	return $content;
}

if (!empty($_POST['id'])) {
	$response = ['status' => 'error', 'content' => ''];
	$apiData  = [
		'userapi'     => 'API',
		'passwordapi' => md5('Duly.2213'),
	]; 
	
	$informacionCliente = getInformacionCliente($apiData, $_POST['id']);
	$data               = getData($apiData, $_POST['id']);

	if (!empty($informacionCliente) && !empty($data)) {
		$response['status']  = 'success';
		$response['content'] = generaContenido($informacionCliente, $data);
	}
	echo json_encode($response);
}

if (!empty($_POST['print']) && !empty($_POST['id']))
{
	$html2pdf = new Html2Pdf('P', 'A4');
	$html2pdf->writeHtml($content);
	$html2pdf->output('reporte.pdf');
}