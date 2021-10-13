<?php

class ModelExtensionShippingArea extends Model
{
    public function getAreas()
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "shipping_area`;");
        return $query->rows;
    }

    public function setAreas($data = array())
    {

    }

    public function install()
    {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "shipping_area` (
			  `shipping_area_id` int(11) NOT NULL AUTO_INCREMENT,
			  `shipping_area_name` varchar(255) NOT NULL,
			  `shipping_area_description` varchar(255) NOT NULL,
			  `shipping_area_code` varchar(255) NOT NULL,
			  `shipping_area_cost` int(11) NOT NULL,
			  `shipping_area_cost_night` int(11) NOT NULL,
			  PRIMARY KEY (`shipping_area_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;"
        );
    }

    public function uninstall()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "shipping_area`;");
    }
}
