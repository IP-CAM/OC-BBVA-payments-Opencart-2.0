<?php
define('TPV_URL', 'https://w3.grupobbva.com/TLPV/tlpv/TLPV_pub_RecepOpModeloServidor');

class ControllerPaymentBBVA extends Controller {

	protected function index() {	
		//<-- OBTENEMOS LAS VARIABLES PARA ENVIAR A LA PASARELA DE PAGO DEL BBVA -->//
		
			// Informacion del terminal BBVA configurado en el panel de administracion
			$idterminal = $this->config->get('bbva_terminal');
			$idcomercio = $this->config->get('bbva_id_comercio');
			$password = $this->config->get('bbva_clave');
			$obfuscated = $this->config->get('bbva_obfuscated');
			
			// Informacion de la orden
			// El idtransaccion sera la concatenacion del order_id y un timestamp
			// Si ponemos solo el order_id como idtransaccion, se pueden producir errores de productos duplicados (sobre todo si tenemos varias tiendas)
			// El idtransaccion son 12 digitos: 6 de comienzo para el order_id, 6 del final para el timstamp
			$order_id = $this->session->data['order_id'];
			$localizador = sprintf('%06d', $order_id);
			$timestamp = substr(time(),0,6);
			$idtransaccion = $localizador.$timestamp;
			$moneda = '978';
			$this->currency->set('EUR');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			$importe =  $this->currency->format($order_info['total'], FALSE, FALSE, FALSE);
			$importe_firma =  str_replace(array('.', ',') , '', $importe);
			
			// Urls
			$urlcomercio = HTTP_SERVER . 'payment/bbva/callback';
			$urlRedir = $this->url->link('payment/bbva/success');
			
			// Firma
			$des_key = $password.substr($idcomercio,0,9)."***";
			$desobfuscated = $this->desobfuscate($obfuscated, $des_key);
			$mensaje = $idterminal.$idcomercio.$idtransaccion.$importe_firma.$moneda.$localizador.$desobfuscated;
			$firma = strtoupper(sha1($mensaje));	
		
		//<-- TERMINAMOS DE OBTENER LAS VARIABLES -->//
		
		
		
		//<-- PETICION PARA ENVIAR A LA PASARELA DE PAGO DEL BBVA -->//
		
			$peticion['idterminal'] = $idterminal;
			$peticion['idcomercio'] = $idcomercio;
			$peticion['idtransaccion'] = $idtransaccion;
			$peticion['moneda'] = $moneda;
			$peticion['importe'] = $importe;
			$peticion['urlcomercio'] = $urlcomercio;
			$peticion['urlredir'] = $urlRedir;
			$peticion['localizador'] = $localizador;
			$peticion['firma'] = $firma;     
			
			$req = "";
			foreach( $peticion as $clave => $valor )
				$req .= "<$clave>$valor</$clave>";
			
		//<-- FIN DE LA PETICION -->//
		

		//<-- VARIABLES PARA EL VIEW -->//
			$this->data['peticion'] = '<tpv><oppago>' . $req . '</oppago></tpv>';
			$this->data['button_confirm'] = $this->language->get('button_confirm');
			$this->data['action'] = TPV_URL;
			$this->load->model('checkout/order');
		
		//<-- FIN DATA -->//
		
		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bbva.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/bbva.tpl';
		} else {
			$this->template = 'default/template/payment/bbva.tpl';
		}	

		$this->render();		
	}
	
	
	public function callback() {
	
		if(!isset($this->request->get['peticion']))
			return;
		
		$peticion = html_entity_decode($this->request->get['peticion'], ENT_QUOTES, 'UTF-8');
		
		// <-- Descomentar para debug --> // 
		//$file = fopen(dirname(__FILE__) . '/debug.txt',"a+");
		//fputs($file,$peticion);
		//fclose($file);
		
		//<-- OBTENEMOS LAS VARIABLES CONFIGURADAS EN EL PANEL DE ADMINISTRACION DE LA PASARELA DE PAGO DEL BBVA -->//
			
			// Informacion del terminal BBVA configurado en el panel de administracion
			$idterminal = $this->config->get('bbva_terminal');
			$idcomercio = $this->config->get('bbva_id_comercio');
			$password = $this->config->get('bbva_clave');
			$obfuscated = $this->config->get('bbva_obfuscated');
			
			$currency = 978;
			$this->currency->set('EUR');
		
		//<-- TERMINAMOS DE OBTENER LAS VARIABLES -->//
		
		
		
		//<-- OBTENEMOS LAS VARIABLES DE LA RESPUESTA DE LA PASARELA DE PAGO DEL BBVA -->//
		
			// Parse XML Request
			$xml_response = (get_magic_quotes_gpc()) ? stripslashes($peticion) : $peticion;
			$xml = html_entity_decode($xml_response);
			$parser = xml_parser_create();
			$ex = xml_parse_into_struct($parser, $xml, $values, $tags);
			xml_parser_free($parser);
			
			foreach ($values as $element) {
				$tag = strtolower($element["tag"]);
				
				if($tag != 'tpv' && $tag != 'respago')
					$response[$tag] = $element["value"];
			}

		
		//<-- CHECK RESPONDE -->//
			$auth = true;
			$accepted = false;
			$error = false;
		
			// Terminal and merchant code
			if ($idterminal != $response['idterminal'] || $idcomercio != $response['idcomercio'])
				return;
				
			// Comprobamos importes
			$moneda = '978';
			$this->currency->set('EUR');
			$order_id = number_format(substr($response['idtransaccion'],0,6));
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			$importe =  $this->currency->format($order_info['total'], FALSE, FALSE, FALSE);
			
			if ($importe != $response['importe'])
				return;

			// Sgnature
			$des_key = $password.substr($idcomercio,0,9)."***";
			$desobfuscated = $this->desobfuscate($obfuscated, $des_key);
			$importe_formateado = str_replace(array('.', ','), '', $response['importe']);
			$signature = strtoupper(sha1($idterminal . $idcomercio . $response['idtransaccion'] . $importe_formateado . $moneda . $response['estado'] . $response['coderror'] . $response['codautorizacion'] . $desobfuscated));
			
		
			if($signature == strtoupper($response['firma']) {
				// Obtenemos el estado de la orden en funcion del estado del pago y los estados configurados en el panel de administracion del modulo
				switch($response['estado']) {
					case 1:
						$order_status_id = $this->config->get('bbva_pending_status_id');
						break;
					case 2:
						$order_status_id = ($response['coderror'] == 0) ? $this->config->get('bbva_completed_status_id') : $this->config->get('bbva_error_status_id');
						break;
					case 3:
						$order_status_id = $this->config->get('bbva_denied_status_id');	
						break;
					case 4:
						$order_status_id = $this->config->get('bbva_failed_status_id');
						break;
					case 5:
						$order_status_id = $this->config->get('bbva_cancel_status_id');
						break;
					default:
						$order_status_id = $this->config->get('bbva_error_status_id');
						break;
				}
				
				// Incluimos un comentario para el propietario:
				// Si la operacion ha sido aceptada, ponemos el codigo de autorizacion. Si hay un error, ponemos el codigo de error y su descripcion
				$this->language->load('payment/bbva');
				if( ($order_status_id == $this->config->get('bbva_completed_status_id')) && ($response['coderror'] == 0) ) {
					$message  = $this->language->get('payment_accepted') . '. ';
					$message .= $this->language->get('auth_code') . ' ' . $response['codautorizacion'] . '. ';
					$message .= $this->language->get('amount') . ' ' . $response['importe'] . ' EUR';
				} else {
					include('err_array.php');
					$message  = $this->language->get('payment_denied') . '. ';
					$message .= $this->language->get('error') . ' ' . $response['coderror'] . ': ' . $err_array[$response['coderror']];
				}
				
				// Asignamos/actualizamos el estado de la orden
				if (!$order_info['order_status_id']) {
					$this->model_checkout_order->confirm($order_id, $order_status_id, '', TRUE);
				} else {
					$this->model_checkout_order->update($order_id, $order_status_id, $message, TRUE);
				}
			}
	}
	
	public function success () {
		if(!isset($this->session->data['order_id']) || $this->session->data['order_id'] == 0) 
			die();
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if($order_info['order_status_id'] == $this->config->get('bbva_completed_status_id'))
			$this->redirect($this->url->link('checkout/success'));
		else
			$this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
	}
	
	function desobfuscate($ofusc_sec_word,$xor_keyword)
	{
		$words = explode (";",$ofusc_sec_word);
		$numWords = count($words);
		$res="";
		
		for ($i=0; $i < $numWords; $i++)
		{
			$x1 = ord($xor_keyword[$i]);
			$x2 = hexdec($words[$i]);
			$r = $x1 ^ $x2;
			$res .= chr($r);
		}
		
		return $res;
	}

}
?>