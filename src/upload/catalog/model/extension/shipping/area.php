<?php
class ModelExtensionShippingArea extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/area');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_area_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_area_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();
            $total = $this->cart->getTotal();
            $area_total = $this->config->get('shipping_area_total');
            if ($this->config->get('shipping_area_areas')){
                foreach ($this->config->get('shipping_area_areas') as $i => $area) {
                    $cost = $area['cost'];
                    $code = 'area'. $i;
                    $title = $area['name'];
                    if ($area_total && $area['cost_total'] !== ''){
                        if ($total < $area_total){
                            $title .= sprintf(
                                '<br><span class="shippingFree">'. $this->language->get('text_second_description') .'</span>',
                                ($area['cost_total'] ? $this->currency->format($this->tax->calculate($area['cost_total'], $this->config->get('shipping_area_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']) : $this->language->get('text_free')),
                                $this->currency->format($this->tax->calculate($area_total, $this->config->get('shipping_area_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
                            );
                        } else{
                            $cost = $area['cost_total'];
                        }
                    }
                    if ($area['description']){
                        $title .= '<br><span class="shippingFree">' . $area['description'] . '</span>';
                    }
                    $quote_data[$code] = array(
                        'code'         => 'area.' . $code,
                        'title'        =>  $title,
                        'cost'         => $area['cost'],
                        'tax_class_id' => $this->config->get('shipping_area_tax_class_id'),
                        'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('shipping_area_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
                    );
                }
            }
            $method_data = array(
				'code'       => 'area',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_area_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}