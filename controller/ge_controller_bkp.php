<?php

class geController extends CommonController
{
    public function __construct()
    {
        $this->params = $_REQUEST;
        switch ($this->params['action']) {
          case 'getForm':
            $this->getForm();
          break;
          case 'saveForm':
            $this->saveForm();
          break;
          case 'insert_lead':
            $this->insert_lead();
          break;
          case 'edit_lead':
            $this->edit_lead();
          break;
          case 'get_leads':
            $this->get_leads();
          break;
          case 'get_leadInfo':
            $this->get_leadInfo();
          break;
          case 'getFormAndDetails':
            $this->getFormAndDetails();
          break;
          case 'add_comment':
            $this->addComment();
          break;
      case 'getRemarks':
            $this->getRemarks();
          break;
      case 'add_reminder':
            $this->addReminder();
          break;
          case 'delete_leads':
                $this->deleteLeads();
              break;
        }
    }
    public function getForm()
    {
        $query = 'select lead_json from clients where client_id='.$_SESSION['ge_permission']['client_id_fk'];
        $result = $this->Query($query);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            $rows = $this->FetchArray($result);
            if(!empty($rows[0]['lead_json'])) {
                    print_r ($rows[0]['lead_json']);
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function saveForm()
    {
        $query = " UPDATE clients SET ";
        $query .= " lead_json='".mysql_escape_string($this->params['lead_json'])."'";
        $query .= " WHERE client_id=".$_SESSION['ge_permission']['client_id_fk'];
        $result = $this->Query($query);
        $affected = $this->AffectedRows();
        if ($affected > 0) {
            echo '1';
        } else {
            echo '0';
        }
    }
    public function insert_lead()
    {
        $query = "INSERT INTO user_leads (client_id_fk,lead_json,created_at) VALUES (".$_SESSION['ge_permission']['client_id_fk'].", '".mysql_escape_string($this->params['lead_json'])."', '".date('Y-m-d H:i:s')."')";
        $result = $this->Query($query);
        $affected = $this->AffectedRows();
        if ($affected > 0) {
            $geid = $this->getInsertID();
            $this->changeLog($geid, 'Lead created');
            return true;
        } else {
            return false;
        }
    }
    public function edit_lead()
    {
        $query = "UPDATE user_leads SET lead_json='".mysql_escape_string($this->params['lead_json'])."'";
        $query .= " WHERE form_id=".$this->params['geid'];
        $query .= " AND client_id_fk=".$_SESSION['ge_permission']['client_id_fk'];
        $result = $this->Query($query);
        $affected = $this->AffectedRows();
        if ($affected > 0) {
            $this->changeLog($this->params['geid'], 'Lead updated');
        } else {
            return false;
        }
    }
    public function getUsersList()
    {
        $query = 'select user_name,user_email,profile_pic from users';
        $result = $this->Query($query);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            $rows = $this->FetchAssoc($result);
            echo json_encode($rows);
        } else {
            echo 0;
        }
    }

    public function get_leads()
    {
        $query = 'SELECT form_id, lead_json, created_at FROM user_leads WHERE active=1 and client_id_fk='.$_SESSION['ge_permission']['client_id_fk'];
        $result = $this->Query($query);
        $rows = array();
        while($assoc = mysql_fetch_assoc($result))
        {
        	$rows[]=$assoc;
        }
        $output['data'] = $rows;
        $Data = str_replace('"{','{',stripslashes(json_encode ($output)));
        $data = str_replace('}"','}',$Data);
        echo $data;
        //echo $a;
    }

    public function addComment()
    {
        $this->changeLog($this->params['id'], $this->params['comment']);
    }
    public function changeLog($lead_id_fk, $log_comments)
    {
        if($log_comments !='') {
            $query = 'INSERT INTO remarks (lead_id_fk, remark, date_remark, user_id_fk) values (';
            $query .= "'".mysql_escape_string($lead_id_fk)."', ";
            $query .= "'".mysql_escape_string($log_comments)."', ";
            $query .= "'".date('Y-m-d H:i:s')."', ";
            $query .= "'".$_SESSION['ge_permission']['userId']."') ";
            $result = $this->Query($query);
            if ($result) {
                //return true;
            } else {
                //return false;
            }
        }
    }
    public function getLeadData()
    {
        $query = 'SELECT lead_json FROM user_leads WHERE form_id='.$this->params['leadId'].' and client_id_fk='.$_SESSION['ge_permission']['client_id_fk'];
        $result = $this->Query($query);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            $rows = $this->FetchArray($result);
            if(!empty($rows[0]['lead_json'])) {
                    return json_decode($rows[0]['lead_json'], true);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getFormAndDetails()
    {
        $client_form = $this->getClientForm();
        $lead_data = $this->getLeadData();
        $final = array();
        foreach ($client_form as $key => $value)
        {
           foreach ($lead_data as $key1 => $value1)
           {
              if($value['name']==$key1) {
                  $value['mapped_value'] = $value1;
                  $final[] = $value;
              }
           }
        }
        echo json_encode($final);
    }
    public function getClientForm()
    {
        $query = 'select lead_json from clients where client_id='.$_SESSION['ge_permission']['client_id_fk'];
        $result = $this->Query($query);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            $rows = $this->FetchArray($result);
            if(!empty($rows[0]['lead_json'])) {
                    return json_decode($rows[0]['lead_json'], true);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getRemarks()
    {
        $query = 'SELECT remark_id,remark,lead_id_fk,date_remark,userName from remarks ';
        $query .= ' LEFT JOIN users ON remarks.user_id_fk=users.userId ';
        $query .= ' WHERE remarks.lead_id_fk='.$this->params['leadId'].' order by remark_id desc';

        $result = $this->Query($query);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            $rows = $this->FetchAssoc($result);
            echo json_encode($rows);
        } else {
            echo 0;
        }
    }
    public function addReminder()
    {
    $dates = getStartEndDates($_POST['daterange']);
    $db_start = $dates['start_date_time'];
    $db_end = $dates['end_date_time'];
    $alert_time = alert_time($db_start, $_POST['alert_before']);

    $sql = 'insert into events_calendar (user_id_fk, event_title, db_start, db_end, alert_before, alert_time, event_created_at) values ';
    $sql .= "('".mysql_escape_string($_SESSION['ge_permission']['userId'])."',";
    $sql .= "'".mysql_escape_string($_POST['event_title'])."',";
    $sql .= "'".mysql_escape_string($db_start)."',";
    $sql .= "'".mysql_escape_string($db_end)."',";
    $sql .= "'".mysql_escape_string($_POST['alert_before'])."',";
    $sql .= "'".mysql_escape_string($alert_time)."',";
    $sql .= "'".date('Y-m-d H:i:s')."')";

    $result = $this->Query($sql);
    if ($result) {
                return true;
            } else {
                return false;
            }
    }
    public function deleteLeads(){
        $leads = $this->params['prd'];
        while (list($key, $val) = @each($leads)) {
            if($val!='CheckAll') {
                $sql = 'UPDATE user_leads SET active=0 WHERE form_id='.$val;
                $this->Query($sql);
                $this->changeLog($val, 'Lead Deleted');
            }
        }
        return true;
    }
    public function __destruct()
    {
        unset($this->params);
        $this->Close();
    }
}
