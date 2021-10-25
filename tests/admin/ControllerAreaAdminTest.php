<?php

require_once __DIR__ . '/../' . getenv('TEST_SUITE') . '/TestCase.php';

class ControllerAreaAdminTest extends \TestCase
{
    const MODULE_TITLE = 'area';

    public function setUp()
    {
        parent::setUp();

        $query = $this->db->query("SELECT permission from ".DB_PREFIX."user_group WHERE name = 'Administrator'");
        $permissions = json_decode($query->row['permission'],true);

        if (!in_array('extension/shipping/area', $permissions['access'])) {
            $permissions['access'][] = 'extension/shipping/area';
            $this->db->query("UPDATE ".DB_PREFIX."user_group SET permission='".$this->db->escape(json_encode($permissions))."' WHERE name = 'Administrator'");
        }


    }

    public function testController()
    {
        $this->login('admin', 'admin');

        $response = $this->dispatchAction('extension/shipping/area');
        $this->assertRegExp('/Area Shipping/', $response->getOutput());
        $this->logout();

    }
}