<?php

class branchController extends CommonController {

    private $params;
    private $conn;
    private $date;

    public function __construct($param, $conn) {
        $this->params = $param;
        $this->conn = $conn;
        $this->date = date('Y-m-d H:i:s');
        switch ($this->params['action']) {
            case 'manage_branch':
                $this->manageBranch();
                break;
            case 'get_branch_details':
                $this->getBranchDetails();
                break;
        }
    }

    private function manageBranch() {
        try {
            if (isset($this->params['bid']) && !empty($this->params['bid'])) {
                $this->editBranch();
            } else {
                $this->insertBranch();
            }
            exit();
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function insertBranch() {
        try {
            $status = $this->params['branch_status'] == 'on' ? 1 : 0;
            $sql = "INSERT INTO branches (client_id_fk, branch_name, branch_location, branch_address, branch_status, branch_created_by) VALUES ";
            $sql .= "('" . $_SESSION['ge_permission']['client_id_fk'] . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['branch_name']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['branch_location']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['branch_address']) . "',";
            $sql .= $status . ",";
            $sql .= $_SESSION['ge_permission']['userId'] . ")";
            echo $sql;
            $this->Query($this->conn, $sql);
            return true;
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function editBranch() {
        try {
            $status = $this->params['branch_status'] == 'on' ? 1 : 0;
            $sql = 'UPDATE branches SET ';
            $sql .= " branch_name='" . mysqli_real_escape_string($this->conn, $this->params['branch_name']) . "',";
            $sql .= " branch_location='" . mysqli_real_escape_string($this->conn, $this->params['branch_location']) . "',";
            $sql .= " branch_address='" . mysqli_real_escape_string($this->conn, $this->params['branch_address']) . "',";
            $sql .= " branch_status=" . $status . ",";
            $sql .= " branch_updated_by='" . $_SESSION['ge_permission']['userId'] . "'";
            $sql .= " WHERE branch_id='" . mysqli_real_escape_string($this->conn, $this->params['bid']) . "' ";
            $this->Query($this->conn, $sql);
            return true;
        } catch (Exception $e) {
            
        }
    }

    private function getBranchDetails() {
        try {
            $sql = "SELECT * FROM branches WHERE branch_id=" . $this->params['bid'];
            $result = $this->Query($this->conn, $sql);
            $rows = $this->FetchAssoc($result);
            echo json_encode($rows[0]);
        } catch (Exception $e) {
            
        }
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
