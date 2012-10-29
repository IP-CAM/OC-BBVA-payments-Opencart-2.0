<?php
class ControllerPaymentBBVA extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/bbva');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {		
			$this->model_setting_setting->editSetting('bbva', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_id_comercio'] = $this->language->get('entry_id_comercio');
		$this->data['entry_terminal'] = $this->language->get('entry_terminal');
		$this->data['entry_clave'] = $this->language->get('entry_clave');
        $this->data['entry_obfuscated'] = $this->language->get('entry_obfuscated');
		$this->data['entry_process_only_completed_status'] = $this->language->get('entry_process_only_completed_status');
		$this->data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$this->data['entry_denied_status'] = $this->language->get('entry_denied_status');	
		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');	
		$this->data['entry_pending_status'] = $this->language->get('entry_pending_status');			
		$this->data['entry_error_status'] = $this->language->get('entry_error_status');
		$this->data['entry_cancel_status'] = $this->language->get('entry_cancel_status');	
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/bbva', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/bbva', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['bbva_id_comercio'])) {
			$this->data['bbva_id_comercio'] = $this->request->post['bbva_id_comercio'];
		} else {
			$this->data['bbva_id_comercio'] = $this->config->get('bbva_id_comercio');
		}
		
		if (isset($this->request->post['bbva_terminal'])) {
			$this->data['bbva_terminal'] = $this->request->post['bbva_terminal'];
		} else {
			$this->data['bbva_terminal'] = $this->config->get('bbva_terminal');
		}

		if (isset($this->request->post['bbva_clave'])) {
			$this->data['bbva_clave'] = $this->request->post['bbva_clave'];
		} else {
			$this->data['bbva_clave'] = $this->config->get('bbva_clave');
		}

        if (isset($this->request->post['bbva_obfuscated'])) {
			$this->data['bbva_obfuscated'] = $this->request->post['bbva_obfuscated'];
		} else {
			$this->data['bbva_obfuscated'] = $this->config->get('bbva_obfuscated');
		}
		
		if (isset($this->request->post['bbva_security'])) {
			$this->data['bbva_security'] = $this->request->post['bbva_security'];
		} else {
			$this->data['bbva_security'] = $this->config->get('bbva_security');
		}

		if (isset($this->request->post['bbva_completed_status_id'])) {
			$this->data['bbva_completed_status_id'] = $this->request->post['bbva_completed_status_id'];
		} else {
			$this->data['bbva_completed_status_id'] = $this->config->get('bbva_completed_status_id');
		}
		
		if (isset($this->request->post['bbva_process_only_completed_status'])) {
			$this->data['bbva_process_only_completed_status'] = $this->request->post['bbva_process_only_completed_status'];
		} else {
			$this->data['bbva_process_only_completed_status'] = $this->config->get('bbva_process_only_completed_status');
		}
		
		if (isset($this->request->post['bbva_pending_status_id'])) {
			$this->data['bbva_pending_status_id'] = $this->request->post['bbva_pending_status_id'];
		} elseif($this->config->get('bbva_process_only_completed_status') == 1) {
			$this->data['bbva_pending_status_id'] = '';
		} else {
			$this->data['bbva_pending_status_id'] = $this->config->get('bbva_pending_status_id');
		}
		
		if (isset($this->request->post['bbva_denied_status_id'])) {
			$this->data['bbva_denied_status_id'] = $this->request->post['bbva_denied_status_id'];
		} elseif($this->config->get('bbva_process_only_completed_status') == 1) {
			$this->data['bbva_pending_status_id'] = '';
		} else {
			$this->data['bbva_denied_status_id'] = $this->config->get('bbva_denied_status_id');
		}
		
		if (isset($this->request->post['bbva_failed_status_id'])) {
			$this->data['bbva_failed_status_id'] = $this->request->post['bbva_failed_status_id'];
		} elseif($this->config->get('bbva_process_only_completed_status') == 1) {
			$this->data['bbva_pending_status_id'] = '';
		} else {
			$this->data['bbva_failed_status_id'] = $this->config->get('bbva_failed_status_id');
		} 
		
		if (isset($this->request->post['bbva_cancel_status_id'])) {
			$this->data['bbva_cancel_status_id'] = $this->request->post['bbva_cancel_status_id'];
		} elseif($this->config->get('bbva_process_only_completed_status') == 1) {
			$this->data['bbva_pending_status_id'] = '';
		} else {
			$this->data['bbva_cancel_status_id'] = $this->config->get('bbva_cancel_status_id');
		}

		if (isset($this->request->post['bbva_error_status_id'])) {
			$this->data['bbva_error_status_id'] = $this->request->post['bbva_error_status_id'];
		} elseif($this->config->get('bbva_process_only_completed_status') == 1) {
			$this->data['bbva_pending_status_id'] = '';
		} else {
			$this->data['bbva_error_status_id'] = $this->config->get('bbva_error_status_id');
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['bbva_geo_zone_id'])) {
			$this->data['bbva_geo_zone_id'] = $this->request->post['bbva_geo_zone_id'];
		} else {
			$this->data['bbva_geo_zone_id'] = $this->config->get('bbva_geo_zone_id');
		} 
		
		$this->load->model('localisation/geo_zone');
									
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['bbva_status'])) {
			$this->data['bbva_status'] = $this->request->post['bbva_status'];
		} else {
			$this->data['bbva_status'] = $this->config->get('bbva_status');
		}
		
		if (isset($this->request->post['bbva_sort_order'])) {
			$this->data['bbva_sort_order'] = $this->request->post['bbva_sort_order'];
		} else {
			$this->data['bbva_sort_order'] = $this->config->get('bbva_sort_order');
		}
		
		$this->template = 'payment/bbva.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bbva')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>