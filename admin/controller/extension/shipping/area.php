<?php
class ControllerExtensionShippingArea extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/area');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_area', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping/area', 'user_token=' . $this->session->data['user_token'], true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/area', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/area', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_area_cost'])) {
			$data['shipping_area_cost'] = $this->request->post['shipping_area_cost'];
		} else {
			$data['shipping_area_cost'] = $this->config->get('shipping_area_cost');
		}

        if (isset($this->request->post['shipping_area_night'])) {
			$data['shipping_area_night'] = $this->request->post['shipping_area_night'];
		} else {
			$data['shipping_area_night'] = $this->config->get('shipping_area_night');
		}

		if (isset($this->request->post['shipping_area_tax_class_id'])) {
			$data['shipping_area_tax_class_id'] = $this->request->post['shipping_area_tax_class_id'];
		} else {
			$data['shipping_area_tax_class_id'] = $this->config->get('shipping_area_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_area_geo_zone_id'])) {
			$data['shipping_area_geo_zone_id'] = $this->request->post['shipping_area_geo_zone_id'];
		} else {
			$data['shipping_area_geo_zone_id'] = $this->config->get('shipping_area_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_area_status'])) {
			$data['shipping_area_status'] = $this->request->post['shipping_area_status'];
		} else {
			$data['shipping_area_status'] = $this->config->get('shipping_area_status');
		}

		if (isset($this->request->post['shipping_area_sort_order'])) {
			$data['shipping_area_sort_order'] = $this->request->post['shipping_area_sort_order'];
		} else {
			$data['shipping_area_sort_order'] = $this->config->get('shipping_area_sort_order');
		}

        $this->load->model('extension/shipping/area');
        $result = $this->model_extension_shipping_area->getAreas();
        var_dump($result);
        $data['shipping_area_areas'] = array();
        if ($result){
            $data['shipping_area_areas'] = $result;
        }
        $data['shipping_area_row'] = count($result);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/area', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/area')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    public function install()
    {
        $this->load->model('extension/shipping/area');
        $this->model_extension_shipping_area->install();
    }

    public function uninstall()
    {
        $this->load->model('extension/shipping/area');
        $this->model_extension_shipping_area->uninstall();
    }
}