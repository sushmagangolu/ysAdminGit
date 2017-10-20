<?php
header('Access-Control-Allow-Origin: *');
class mobileController extends CommonController {

    private $params;
    private $conn;

    public function __construct($param, $conn) {
        $this->params = $param;
        $this->conn = $conn;
        switch($this->params['action']){
            case 'getLeads':
            $this->getLeads();
        }
    }

    public function get_leads() {
        try {
            $query = 'SELECT form_id, lead_json FROM user_leads WHERE active=1 and client_id_fk=10 ORDER BY form_id DESC  LIMIT 0, 100';
            $result = $this->Query($this->conn, $query);
            $rows = array();
            $rows = $this->FetchArray($result);
            print json_encode($rows);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
