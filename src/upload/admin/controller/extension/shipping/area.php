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

        if (isset($this->request->post['shipping_area_areas'])) {
			$data['shipping_area_areas'] = $this->request->post['shipping_area_areas'];
		} else {
			$data['shipping_area_areas'] = $this->config->get('shipping_area_areas');
		}
        if (isset($this->request->post['shipping_area_night_time_e'])) {
			$data['shipping_area_night_time_e'] = $this->request->post['shipping_area_night_time_e'];
		} else {
			$data['shipping_area_night_time_e'] = $this->config->get('shipping_area_night_time_e');
		}
        if (isset($this->request->post['shipping_area_night_time_s'])) {
			$data['shipping_area_night_time_s'] = $this->request->post['shipping_area_night_time_s'];
		} else {
			$data['shipping_area_night_time_s'] = $this->config->get('shipping_area_night_time_s');
		}

        $data['shipping_area_row'] = count($data['shipping_area_areas']);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/area', $data));
	}

	protected function validate() {
//        TODO: validate
		if (!$this->user->hasPermission('modify', 'extension/shipping/area')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        foreach ($this->request->post['shipping_area_areas'] as $area_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 128)) {
                $this->error['name'][$area_id] = $this->language->get('error_name');
            }
            if (utf8_strlen($value['description']) > 256) {
                $this->error['description'][$area_id] = $this->language->get('error_description');
            }
            if (!is_numeric($value['cost'])) {
                $this->error['cost'][$area_id] = $this->language->get('error_cost');
            }
            if (!is_numeric($value['cost_night'])) {
                $this->error['cost_night'][$area_id] = $this->language->get('error_cost_night');
            }
        }
        if (!preg_match("/\d\d:\d\d/", $this->request->post['shipping_area_night_time_e'])){
            $this->error['night_time'] = $this->language->get('error_night_time');
        }
        if (!preg_match("/\d\d:\d\d/", $this->request->post['shipping_area_night_time_s'])){
            $this->error['night_time'] = $this->language->get('error_night_time');
        }

		return !$this->error;
	}


}