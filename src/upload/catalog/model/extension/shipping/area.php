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
            $night = false;
            if (!empty($this->session->data['delivery_date']) && $this->config->get('shipping_area_night')) {
                $start_time = $this->config->get('shipping_area_night_time_s');
                $end_time = $this->config->get('shipping_area_night_time_e');
                $delivery_dt = DateTime::createFromFormat('Y-m-d H:i', $this->session->data['delivery_date']);
                $date = $delivery_dt->format('Y-m-d');
                $delivery_ts = $delivery_dt->getTimestamp();
                $start_ts = strtotime($date  ." ". $start_time);
                $end_ts = strtotime($date  ." ". $end_time);
                if ($start_ts > $end_ts){
                    $end_ts = strtotime($date  ." ". $end_time . "+1 days");
                    $previousDayEnd = strtotime($date  ." ". $end_time );
                } else{
                    $previousDayEnd = strtotime($date  ." ". $end_time . "-1 days");
                }

                if (in_array($delivery_ts, range($start_ts, $end_ts)) || ($delivery_ts < $start_ts && $delivery_ts < $previousDayEnd)) {
                    $night = true;
                }
            }
            if ($this->config->get('shipping_area_areas')){
                foreach ($this->config->get('shipping_area_areas') as $i => $area) {
                    $code = 'area'. $i;
                    $title = $area['name'];
                    if ($area['description']){
                        $title .= '<br><span class="shippingFree">' . $area['description'] . '</span>';
                    }
                    $cost = $area['cost'];
                    $cost_total = floatval($area['cost_total']);
                    if ($area['cost_night'] && $night){
                        $area['cost_night'] = preg_replace(["/\s/", "/\,/"], ["", "."], $area['cost_night']);
                        $pattern = '/[\+\-\*]{1}(?=(\d+))/';
                        preg_match($pattern, $area['cost_night'], $pref);
                        $night_cost = preg_replace("/[\+\-\*]/", "", $area['cost_night']);
                        if ($pref){
                            switch ($pref[0]){
                                case "+":
                                    $cost = $cost + $night_cost;
                                    $cost_total = $cost_total + $night_cost;
                                    break;
                                case "-":
                                    $cost = $cost - $night_cost;
                                    $cost_total = $cost_total - $night_cost;
                                    break;
                                case "*":
                                    $cost = $cost * $night_cost;
                                    $cost_total = $cost_total * $night_cost;
                                    break;
                                default:
                                    $cost = $night_cost;
                                    $cost_total = $night_cost;
                            }
                        } else{
                            $cost = $night_cost;
                        }
                    }
                    if ($area_total && $area['cost_total'] !== ''){
                        if ($total < $area_total){
                            $title .= sprintf(
                                '<br><span class="shippingFree">'. $this->language->get('text_second_description') .'</span>',
                                ($cost_total ? $this->currency->format($this->tax->calculate($cost_total, $this->config->get('shipping_area_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']) : $this->language->get('text_free')),
                                $this->currency->format($this->tax->calculate($area_total, $this->config->get('shipping_area_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
                            );
                        } else{
                            $cost = $cost_total;
                        }
                    }

                    $quote_data[$code] = array(
                        'code'         => 'area.' . $code,
                        'title'        =>  $title,
                        'cost'         => $cost,
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