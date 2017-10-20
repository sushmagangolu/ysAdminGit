<?php

class triggerController extends CommonController {

    private $params;
    private $conn;
    private $date;

    public function __construct($param, $conn) {
        $this->params = $param;
        $this->conn = $conn;
        $this->date = date('Y-m-d H:i:s');
        switch ($this->params['action']) {
            case 'manage_trigger':
                $this->manageTrigger();
                break;
            case 'get_details':
                $this->getTriggerDetails();
                break;
            case 'delete':
                $this->deleteTrigger();
                break;
        }
    }

    private function manageTrigger() {
        try {
            if (isset($this->params['tid']) && !empty($this->params['tid'])) {
                $this->editTrigger();
            } else {
                $this->insertTrigger();
            }
            exit();
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function insertTrigger() {
        try {
            $stc = $this->params['send_to_customer'] == 'on' ? 1 : 0;
            $sql = "INSERT INTO ge_triggers (trigger_name, trigger_type, client_id_fk, trigger_status, trigger_send_to, send_to_customer, trigger_content, trigger_subject, trigger_content_customer, trigger_created_by) VALUES ";
            $sql .= "('" . mysqli_real_escape_string($this->conn, $this->params['trigger_name']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['trigger_type']) . "',";
            $sql .= "'" . $_SESSION['ge_permission']['client_id_fk'] . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['status_ge']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['users_ge']) . "',";
            $sql .= "'" . $stc . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['trigger_content']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['trigger_subject']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['trigger_content_customer']) . "',";
            $sql .= "'" . $_SESSION['ge_permission']['userId'] . "')";
            $this->Query($this->conn, $sql);
            return true;
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function editTrigger() {
        try {
            $stc = $this->params['send_to_customer'] == 'on' ? 1 : 0;
            $sql = 'UPDATE ge_triggers SET ';
            $sql .= " trigger_name='" . mysqli_real_escape_string($this->conn, $this->params['trigger_name']) . "',";
            $sql .= " trigger_status='" . mysqli_real_escape_string($this->conn, $this->params['status_ge']) . "',";
            $sql .= " trigger_send_to='" . mysqli_real_escape_string($this->conn, $this->params['users_ge']) . "',";
            $sql .= " send_to_customer='" . $stc . "',";
            $sql .= " trigger_content='" . mysqli_real_escape_string($this->conn, $this->params['trigger_content']) . "',";
            $sql .= " trigger_subject='" . mysqli_real_escape_string($this->conn, $this->params['trigger_subject']) . "',";
            $sql .= " trigger_content_customer='" . mysqli_real_escape_string($this->conn, $this->params['trigger_content_customer']) . "',";
            $sql .= " trigger_updated_by='" . $_SESSION['ge_permission']['userId'] . "'";
            $sql .= " WHERE trigger_id='" . mysqli_real_escape_string($this->conn, $this->params['tid']) . "' ";
            $this->Query($this->conn, $sql);
            return true;
        } catch (Exception $e) {
            
        }
    }

    private function getTriggerDetails() {
        try {
            $sql = "SELECT * FROM ge_triggers WHERE trigger_id=" . $this->params['tid'];
            $result = $this->Query($this->conn, $sql);
            $rows = $this->FetchAssoc($result);
            echo json_encode($rows[0]);
        } catch (Exception $e) {
            
        }
    }

    private function deleteTrigger() {
        try {
            $sql = "DELETE FROM ge_triggers WHERE trigger_id=" . $this->params['tid'];
            $result = $this->Query($this->conn, $sql);
            return true;
        } catch (Exception $e) {
            
        }
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
