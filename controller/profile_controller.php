<?php

class profileController extends CommonController {

    private $params;
    private $conn;
    private $date;
    private $base;

    public function __construct($param, $conn) {
        $this->params = $param;
        $this->conn = $conn;
        $this->date = date('Y-m-d H:i:s');
        $this->base = $_SERVER['HTTP_HOST'] . "/";
        switch ($this->params['action']) {
            case 'edit_client':
                $this->editClient();
                break;
            case 'sms_settings':
                $this->updateSMSSettings();
                break;
            case 'getProfileDetails':
                $this->getProfileDetails();
                break;
            case 'general_settings':
                $this->updateGeneralSettings();
                break;
            case 'ea_settings':
                $this->updateFollowUpSettings();
                break;
            case 'misc_settings':
                $this->addMarketingEmail();
                break;
            case 'delete_email':
                $this->deleteMarketingEmail();
                break;
            case 'report_settings':
                $this->updateReportSettings();
                break;
            case 'manageFP':
                $this->manageFP();
                break;
        }
    }

    private function manageFP(){
      try {
        if($this->params['upd'] == 2) {
          $sql = "DELETE FROM follow_up WHERE follow_up_id=".$this->params['follow_up_id'];
        } else {
          $sql = "UPDATE follow_up SET follow_up_send=".$this->params['upd']." WHERE follow_up_id=".$this->params['follow_up_id'];
        }
        //echo $sql;
        $this->Query($this->conn, $sql);
      } catch (Exception $e) {
        echo $e;
      }
    }

    private function addMarketingEmail() {
        try {
            $query = 'INSERT INTO emails_marketing (client_id_fk, email_market) values (';
            $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_id']) . "', ";
            $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['m_email']) . "') ";

            $result = $this->Query($this->conn, $query);
        } catch (Exception $exc) {
            echo $exc;
        }
    }

    private function deleteMarketingEmail() {
        try {
            $this->Query($GLOBALS['con'], 'DELETE FROM emails_marketing WHERE em_id=' . $this->params['em_id']);
            return true;
        } catch (Exception $exc) {
            echo $exc;
        }
    }

    private function getProfileDetails() {
        $data = [];
        $data['client'] = $this->getClientData();
        $data['email_templates'] = $this->getEmailTemplates();
        $data['follow_up'] = $this->getFollowUps();
        $data['emails'] = $this->getEmails();
        echo json_encode($data);
    }

    private function getEmails() {
        try {
            $query = "SELECT * FROM emails_marketing WHERE client_id_fk=" . $this->params['client_id'];
            $result = $this->Query($this->conn, $query);
            $row_count = $this->FetchNum($result);
            if ($row_count > 0) {
                $rows = $this->FetchAssoc($result);
                return $rows;
            } else {
                return false;
            }
        } catch (Exception $e) {
            die($e);
        }
    }

    private function getFollowUps() {
        try {
            $query = "SELECT * FROM follow_up LEFT JOIN email_templates ON email_template_fk=template_id WHERE follow_up.client_id_fk=" . $this->params['client_id'];
            $result = $this->Query($this->conn, $query);
            $row_count = $this->FetchNum($result);
            if ($row_count > 0) {
                $rows = $this->FetchAssoc($result);
                return $rows;
            } else {
                return false;
            }
        } catch (Exception $e) {
            die($e);
        }
    }

    private function getClientData() {
        try {
            $query = "SELECT * FROM clients WHERE client_id=" . $this->params['client_id'];
            $result = $this->Query($this->conn, $query);
            $row_count = $this->FetchNum($result);
            if ($row_count > 0) {
                $rows = $this->FetchAssoc($result);
                return $rows[0];
            } else {
                return false;
            }
        } catch (Exception $e) {
            die($e);
        }
    }

    private function getEmailTemplates() {
        try {
            $query = 'select template_id, template_name from email_templates where client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
            $result = $this->Query($this->conn, $query);
            $row_count = $this->FetchNum($result);
            if ($row_count > 0) {
                $rows = $this->FetchAssoc($result);
                return $rows;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function updateSMSSettings() {
        try {
            $sql = "UPDATE clients SET ";
            $sql .= " sender_id='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['sender_id']) . "',";
            $sql .= " promotional_sms='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['promo_sms'] == 'on' ? 1 : 0) . "',";
            $sql .= " promotional_sms_text='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['promo_text']) . "' ";
            $sql .= " WHERE client_id=" . $this->params['client_id'];
            $this->Query($this->conn, $sql);
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function updateReportSettings() {
        try {
            $sql = "UPDATE clients SET ";
            $sql .= " daily_report='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['daily_reports'] == 'on' ? 1 : 0) . "',";
            $sql .= " weekly_report='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['weekly_reports'] == 'on' ? 1 : 0) . "',";
            $sql .= " monthly_report='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['monthly_reports'] == 'on' ? 1 : 0) . "',";
            $sql .= " report_email_fk='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['report_email']) . "' ";
            $sql .= " WHERE client_id=" . $this->params['client_id'];
            $this->Query($this->conn, $sql);
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function updateGeneralSettings() {
        try {
            $sql = "UPDATE clients SET ";
            $sql .= " email_automation='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['email_automation'] == 'on' ? 1 : 0) . "',";
            $sql .= " marketing_email_fk='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['marketing_email']) . "',";
            $sql .= " ack_email='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['ack_email'] == 'on' ? 1 : 0) . "',";
            $sql .= " email_notifications='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['email_notifications'] == 'on' ? 1 : 0) . "',";
            $sql .= " sms_notifications='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['sms_notifications'] == 'on' ? 1 : 0) . "',";
            $sql .= " ack_email_tpl='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['ack_email_tpl']) . "', ";
            $sql .= " round_robin='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['round_robin'] == 'on' ? 1 : 0) . "' ";
            $sql .= " WHERE client_id=" . $this->params['client_id'];
            $this->Query($this->conn, $sql);
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function updateFollowUpSettings() {
        try {
            //$delQuery = "DELETE FROM follow_up WHERE client_id_fk=" . $this->params['client_id'] . " AND follow_up_order=" . $this->params['follow_up_order'];
            //$this->Query($this->conn, $delQuery);

            $query = "INSERT into follow_up(client_id_fk, email_id_fk, email_template_fk, follow_up_after, follow_up_at, lead_status, follow_up_send) VAlUES (";
            $query .= $this->params['client_id'] . ",";
            $query .= mysqli_real_escape_string($GLOBALS['con'], $this->params['follow_email']) . ", ";
            $query .= mysqli_real_escape_string($GLOBALS['con'], $this->params['email_tpl']) . ", ";
            $query .= $this->params['after'] . ",";
            $query .= $this->params['time'] . ",";
            $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['lead_status']) . "', ";
            $query .= $this->params['follow_up_send'] == 'on' ? 1 : 0;
            $query .= ")";
            echo $query;
            $this->Query($this->conn, $query);
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function editClient() {
        if ($this->checkEmailExistsWhileEdit()) {
            $sql = 'UPDATE clients SET ';
            $sql .= " client_name='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_name']) . "',";
            $sql .= " client_website='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_website']) . "',";
            $sql .= " client_email='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_email']) . "',";
            $sql .= " client_phone='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_phone']) . "',";
            $sql .= " client_address='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_address']) . "',";
            $sql .= " primary_contact_name='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['primary_contact_name']) . "',";
            $sql .= " social_facebook='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['social_facebook']) . "',";
            $sql .= " social_gplus='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['social_gplus']) . "',";
            $sql .= " social_twitter='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['social_twitter']) . "',";
            $sql .= " facebook_page_id='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['facebook_page_id']) . "',";
            if (!empty($this->params['image'])) {
                $sql .= " client_image='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['image']) . "', ";
            }
            $sql .= " primary_contact_phone='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['primary_contact_phone']) . "' ";
            $sql .= " WHERE client_id='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_id']) . "' ";
            $this->Query($this->conn, $sql);
            echo 1;
        } else {
            echo 0;
        }
    }

    private function checkEmailExistsWhileEdit() {
        $sql = "SELECT client_email FROM clients WHERE client_email='" . $this->params['client_email'] . "'";
        $sql .= " AND client_id !='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_id']) . "' ";

        $result = $this->Query($this->conn, $sql);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            return false;
        } else {
            return true;
        }
    }

    private function deleteClient() {
        $this->Query($GLOBALS['con'], 'UPDATE clients SET client_active=0 WHERE client_id=' . $this->params['client_id']);
        $this->Query($GLOBALS['con'], 'UPDATE users  SET user_active=0 WHERE client_id_fk=' . $this->params['client_id']);
        return true;
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
