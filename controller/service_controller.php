<?php

class serviceController extends CommonController {

  private $params;
  private $conn;
  public function __construct($param, $conn) {
    $this->params = $param;
    $this->conn = $conn;
  }
  public function addComment() {
    $this->changeLog($this->params['id'], $this->params['comment']);
  }
  public function changeLog($lead_id_fk, $log_comments) {
    if ($log_comments != '') {
      $query = 'INSERT INTO remarks (lead_id_fk, remark, date_remark, user_id_fk) values (';
      $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $lead_id_fk) . "', ";
      $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $log_comments) . "', ";
      $query .= "'" . date('Y-m-d H:i:s') . "', ";
      $query .= "'" . $_SESSION['ge_permission']['userId'] . "') ";
      $result = $this->Query($this->conn, $query);
      if ($result) {
        //return true;
      } else {
        //return false;
      }
    }
  }
  public function getForm() {
    $query = 'select lead_json from clients where client_id=' . $_SESSION['ge_permission']['client_id_fk'];
    $result = $this->Query($this->conn, $query);
    $row_count = $this->FetchNum($result);
    if ($row_count > 0) {
      $rows = $this->FetchArray($result);
      if (!empty($rows[0]['lead_json'])) {
        print_r($rows[0]['lead_json']);
      } else {
        echo 0;
       }
     } else {
       echo 0;
     }
   }
   public function saveForm() {
     $query = " UPDATE clients SET ";
     $query .= " lead_json='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['lead_json']) . "'";
     $query .= " WHERE client_id=" . $_SESSION['ge_permission']['client_id_fk'];
     $result = $this->Query($this->conn, $query);
     //$affected = $this->AffectedRows($this->conn);
     if ($affected > 0) {
       $this->saveSource();
       $this->saveStatus();
       return true;
     } else {
       return false;
     }
   }
   private function saveSource() {
     $sources = array();
     $client_form = json_decode($this->params['lead_json'], true);
     foreach ($client_form as $key => $value) {
       if ($value['name'] == 'source') {
         foreach ($value['values'] as $key => $value) {
           array_push($sources, $value['label']);
         }
       }
     }
     $query = "UPDATE user_source SET sources='" . mysqli_real_escape_string($GLOBALS['con'], json_encode($sources)) . "' ";
     $query .= " WHERE client_id_fk=" . $_SESSION['ge_permission']['client_id_fk'];
     $this->Query($this->conn, $query);
   }
   private function saveStatus() {
     $statuses = array();
     $client_form = json_decode($this->params['lead_json'], true);
     foreach ($client_form as $key => $value) {
       if ($value['name'] == 'status') {
         foreach ($value['values'] as $key => $value) {
           array_push($statuses, $value['label']);
         }
       }
     }
     $query = "UPDATE user_status SET statuses='" . mysqli_real_escape_string($GLOBALS['con'], json_encode($statuses)) . "' ";
             $query .= " WHERE client_id_fk=" . $_SESSION['ge_permission']['client_id_fk'];
             $this->Query($this->conn, $query);
           }
           public function insert_lead() {
             /* if (!empty($this->params['crDate'])) {
             $date = $this->params['crDate'];
           } else {
           $date = date('Y-m-d H:i:s');
         } */
         $lead = json_decode($this->params['lead_json'], true);
         $query = "INSERT INTO user_leads (client_id_fk, lead_json, created_at, status, source, lead_name, lead_email, lead_phone, event_date, registration_date, check_in, check_out) VALUES";        $query .= "(" . $_SESSION['ge_permission']['client_id_fk'] . ", ";        $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['created_at'])) . "', ";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $lead['created_at']) . "', ";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $lead['status']) . "', ";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $lead['source']) . "', ";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $lead['name']) . "', ";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $lead['email']) . "', ";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], $lead['phone']) . "',";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['event_date'])) . "',";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['registration_date'])) . "',";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['check_in'])) . "',";
         $query .= "'" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['check_out'])) . "')";
         echo $query;
         $this->Query($this->conn, $query);
         $affected = $this->AffectedRows($this->conn);
         if ($affected > 0) {
           $geid = $this->getInsertID($this->conn);
           $this->changeLog($geid, 'Lead created');
           if (isset($this->params['message']) && !empty($this->params['message'])) {
             $this->changeLog($geid, $this->params['message']);
           }
           $this->manageTriggers($lead);
           return true;
         } else {
           return false;
         }
       }
       public function edit_lead() {
         $lead = json_decode($this->params['lead_json'], true);
         //print_r($lead);        $query = "UPDATE user_leads SET ";
         $query .= " lead_json='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['lead_json']) . "' ";
         //$query .= " status='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['status']) . "',";
         //$query .= " source='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['source']) . "'";
         if (array_key_exists("status", $lead)) {
           $query .= ", status='" . mysqli_real_escape_string($GLOBALS['con'], $lead['status']) . "'";
         }
         if (array_key_exists("source", $lead)) {
           $query .= ", source='" . mysqli_real_escape_string($GLOBALS['con'], $lead['source']) . "'";
         }
         if (array_key_exists("name", $lead)) {
           $query .= ", lead_name='" . mysqli_real_escape_string($GLOBALS['con'], $lead['name']) . "'";
         }
         if (array_key_exists("phone", $lead)) {
           $query .= ", lead_phone='" . mysqli_real_escape_string($GLOBALS['con'], $lead['phone']) . "'";
         }
         if (array_key_exists("email", $lead)) {
           $query .= ", lead_email='" . mysqli_real_escape_string($GLOBALS['con'], $lead['email']) . "'";
         }
         if (array_key_exists("event_date", $lead)) {
           $query .= ", event_date='" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['event_date'])) . "'";
         }
         if (array_key_exists("registration_date", $lead)) {
           $query .= ", registration_date='" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['registration_date'])) . "'";
         }
         if (array_key_exists("created_at", $lead)) {
           $query .= ", created_at='" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['created_at'])) . "'";
         }
         if (array_key_exists("check_in", $lead)) {
           $query .= ", check_in='" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['check_in'])) . "'";
         }
         if (array_key_exists("check_out", $lead)) {
           $query .= ", check_out='" . mysqli_real_escape_string($GLOBALS['con'], formatDate($lead['check_out'])) . "'";
         }
         $query .= " WHERE form_id=" . $this->params['geid'];
         $query .= " AND client_id_fk=" . $_SESSION['ge_permission']['client_id_fk'];
          //echo $query;
          $result = $this->Query($this->conn, $query);
          if ($result) {
            $this->changeLog($this->params['geid'], 'Lead updated');
            $this->manageTriggers($lead);
          } else {
            return false;
          }
        }
        public function getUsersList() {
          $query = 'select user_name,user_email,profile_pic from users';
          $result = $this->Query($this->conn, $query);
          $row_count = $this->FetchNum($result);
          if ($row_count > 0) {
            $rows = $this->FetchAssoc($result);
            echo json_encode($rows);
          } else {
            echo 0;
          }
        }
        public function get_leads() {
          $query = 'SELECT form_id, lead_json, created_at, fb_form_name, assign_to FROM user_leads ';
          $query .= ' WHERE active=1 AND client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
          if ($_SESSION['ge_permission']['userType'] == 'normal') {
            $query .= ' AND assign_to IN(0, ' . $_SESSION['ge_permission']['userId'] . ')';
          }
          $query .= ' ORDER BY form_id DESC  LIMIT 0, 3000';
          //echo $query;
          $result = $this->Query($this->conn, $query);
          $rows = array();
          while ($assoc = mysqli_fetch_assoc($result)) {
            $a = array();
            $a['lead_data'] = $assoc;
            $a['comment'] = $this->getLatestComment($assoc['form_id']);
            //$rows['comment'] = $latestComment;
            array_push($rows, $a);
          }
          //print_r(json_encode($rows, true));
          $output['data'] = $rows;
          $Data = str_replace('"{', '{', stripslashes(json_encode($output, true)));
            $data = str_replace('}"', '}', $Data);
            echo $data;
          }
          public function get_leads_all() {
            $query = 'SELECT user_leads.form_id, user_leads.lead_json, user_leads.created_at, clients.client_name FROM user_leads LEFT JOIN clients ON user_leads.client_id_fk = clients.client_id WHERE active=1 order by form_id DESC  LIMIT 0, 2000';
            //echo $query;
            $result = $this->Query($this->conn, $query);
            $rows = array();
            while ($assoc = mysqli_fetch_assoc($result)) {
              $a = array();
              $a['lead_data'] = $assoc;
              //$a['comment'] = $this->getLatestComment($assoc['form_id']);
              //$rows['comment'] = $latestComment;
              array_push($rows, $a);
            }
            //print_r(json_encode($rows, true));
            $output['data'] = $rows;
            $Data = str_replace('"{', '{', stripslashes(json_encode($output, true)));
              $data = str_replace('}"', '}', $Data);        echo $data;
            }
            public function getLatestComment($a) {
              $query = "SELECT remark, remark_type FROM remarks WHERE lead_id_fk=" . $a . " and remark !='Lead Updated'  order by remark_id desc limit 1";
              $result = $this->Query($this->conn, $query);
              $row_count = $this->FetchNum($result);
              if ($row_count > 0) {
                $rows = $this->FetchAssoc($result);
                return $rows[0];
              } else {
                return '--';
              }
            }
            public function getLeadData() {
              $query = 'SELECT lead_json, created_at FROM user_leads WHERE form_id=' . $this->params['leadId'] . ' and client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
              $result = $this->Query($this->conn, $query);
              $row_count = $this->FetchNum($result);
              if ($row_count > 0) {
                $rows = $this->FetchArray($result);
                if (!empty($rows[0]['lead_json'])) {
                  return $rows[0];
                } else {
                  return false;
                }
              } else {
                return false;
              }
            }
            public function getFormAndDetails() {
              $client_form = $this->getClientForm();
              $lead_info = $this->getLeadData();
              //print_r($lead_info);
              $lead_data = json_decode($lead_info['lead_json'], true);
              //print_r($lead_data);
              $lead_date = $lead_info['created_at'];
              $final = array();
              foreach ($client_form as $key => $value) {
                $final[] = $value;
                foreach ($lead_data as $key1 => $value1) {
                  if ($value['name'] == 'date') {
                    $final[$key]['mapped_value'] = $lead_date;
                  }
                  if ($value['name'] == $key1 && $value['name'] != 'date') {
                    $final[$key]['mapped_value'] = $value1;
                  }
                }
              }
              echo json_encode($final);
            }
            public function getClientForm() {
              $query = 'select lead_json from clients where client_id=' . $_SESSION['ge_permission']['client_id_fk'];
              $result = $this->Query($this->conn, $query);
              $row_count = $this->FetchNum($result);
              if ($row_count > 0) {
                $rows = $this->FetchArray($result);
                if (!empty($rows[0]['lead_json'])) {
                  return json_decode($rows[0]['lead_json'], true);
                } else {
                  return false;
                }
              } else {
                return false;
              }
            }
            public function getRemarks() {
              $query = 'SELECT remark_id,remark,lead_id_fk,date_remark,userName, remark_type from remarks ';
              $query .= ' LEFT JOIN users ON remarks.user_id_fk=users.userId ';
              $query .= ' WHERE remarks.lead_id_fk=' . $this->params['leadId'] . ' order by remark_id desc';
              $result = $this->Query($this->conn, $query);        $row_count = $this->FetchNum($result);
              if ($row_count > 0) {
                $rows = $this->FetchAssoc($result);
                echo json_encode($rows);
              } else {
                echo 0;
              }
            }
            public function addReminder() {
              $dates = getStartEndDates($this->params['daterange']);
              $db_start = $dates['start_date_time'];
              $db_end = $dates['end_date_time'];
              $alert_time = alert_time($db_start, $this->params['alert_before']);
              $sql = 'insert into events_calendar (user_id_fk, client_id_fk, event_title, db_start, db_end, alert_before, alert_time, lead_id_fk, event_created_at ) values ';
              $sql .= "('" . mysqli_real_escape_string($GLOBALS['con'], $_SESSION['ge_permission']['userId']) . "',";
              $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $_SESSION['ge_permission']['client_id_fk']) . "',";
              $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['event_title']) . "',";
              $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $db_start) . "',";
              $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $db_end) . "',";
              $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['alert_before']) . "',";
              $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $alert_time) . "',";
              $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['id']) . "',";
              $sql .= "'" . date('Y-m-d H:i:s') . "')";        $result = $this->Query($this->conn, $sql);
              if ($result) {
                return true;
              } else {
                return false;
              }
            }
            public function deleteLeads() {
              $leads = $this->params['prd'];
              while (list($key, $val) = @each($leads)) {
                if ($val != 'CheckAll') {
                  $sql = 'UPDATE user_leads SET active=0 WHERE form_id=' . $val;
                  $this->Query($this->conn, $sql);
                  $this->changeLog($val, 'Lead Deleted');
                }
              }
              return true;
            }
            public function getEmails($leadIds) {
              $a = '';
              while (list($key, $val) = @each($leadIds)) {
                $a .= $val . ',';
              }
              $ids = trim($a, ',');
              $query = 'SELECT lead_json FROM user_leads WHERE form_id IN(' . $ids . ') AND client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
              $result = $this->Query($this->conn, $query);        $row_count = $this->FetchNum($result);
              if ($row_count > 0) {            $rows = $this->FetchArray($result);            $emails = '';
                foreach ($rows as $key => $value) {                foreach (json_decode($value[0], true) as $key1 => $value1) {
                  if ($key1 == 'email') {                        $emails .= $value1 . ',';
                  }
                }
              }
              return trim($emails, ',');
            } else {
              return false;
            }
          }
          public function get_trash() {
            $query = 'SELECT form_id, lead_json, created_at, fb_form_name FROM user_leads WHERE active=0 and client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'] . ' order by form_id DESC';
            //$query = 'SELECT form_id, lead_json, created_at FROM user_leads WHERE active=0 and client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
            $result = $this->Query($this->conn, $query);
            $rows = array();
            while ($assoc = mysqli_fetch_assoc($result)) {
              $a = array();
              $a['lead_data'] = $assoc;
              $a['comment'] = $this->getLatestComment($assoc['form_id']);
              //$rows['comment'] = $latestComment;
              array_push($rows, $a);
            }
            //print_r(json_encode($rows, true));
            $output['data'] = $rows;
            $Data = str_replace('"{', '{', stripslashes(json_encode($output, true)));
              $data = str_replace('}"', '}', $Data);
              echo $data;
            }
            public function restoreLeads() {
              $leads = $this->params['prd'];
              while (list($key, $val) = @each($leads)) {
                if ($val != 'CheckAll') {
                  $sql = 'UPDATE user_leads SET active=1 WHERE form_id=' . $val;
                  $this->Query($this->conn, $sql);
                  $this->changeLog($val, 'Lead Restored');
                }
              }
              return true;
            }
            public function getFormBuild() {
              $query = 'select lead_json from clients where client_id=' . $this->params['id'];
              $result = $this->Query($this->conn, $query);
              $row_count = $this->FetchNum($result);
              if ($row_count > 0) {
                $rows = $this->FetchArray($result);
                if (!empty($rows[0]['lead_json'])) {
                  print_r($rows[0]['lead_json']);
                } else {
                  echo 0;
                }
              } else {
                echo 0;
              }
            }
            public function saveFormBuild() {
              $query = " UPDATE clients SET ";
              $query .= " lead_json='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['lead_json']) . "'";
              $query .= " WHERE client_id=" . $this->params['id'];        $result = $this->Query($this->conn, $query);
              $affected = $this->AffectedRows($this->conn);
              if ($affected > 0) {
                $this->saveSourceStatusBuild($this->params['id']);
                return true;
              } else {
                return false;
              }
            }
            public function saveSourceBuild($id) {
              $sources = array();
              $client_form = json_decode($this->params['lead_json'], true);
              foreach ($client_form as $key => $value) {
                if ($value['name'] == 'source') {
                  foreach ($value['values'] as $key => $value) {
                    array_push($sources, $value['label']);
                  }
                }
              }
              //$query = "UPDATE user_source SET sources='" . mysqli_real_escape_string($GLOBALS['con'], json_encode($sources)) . "' ";
              //$query .= " WHERE client_id_fk=" . $id;        $delQuery = "DELETE FROM user_source WHERE client_id_fk=" . $id;
              $this->Query($this->conn, $delQuery);
              $query = "INSERT into user_source(client_id_fk, sources) VAlUES (" . $id . ", '" . mysqli_real_escape_string($GLOBALS['con'], json_encode($sources)) . "')";
              $this->Query($this->conn, $query);
              return true;
            }
            public function saveSourceStatusBuild($id) {
              $statuses = '[';
              $sources = '[';
              $client_form = json_decode($this->params['lead_json'], true);
              foreach ($client_form as $key => $value) {
                if ($value['name'] == 'status') {
                  foreach ($value['values'] as $key => $value) {
                    $statuses .= '{"status":"' . $value['label'] . '"},';
                  }
                }
                if ($value['name'] == 'source') {
                  foreach ($value['values'] as $key => $value) {
                    $sources .= '{"source":"' . $value['label'] . '"},';
                  }
                }
              }
              $statuses = trim($statuses, ',') . ']';
              $sources = trim($sources, ',') . ']';
              $query = "UPDATE clients SET ";
              $query .= " status_json='" . mysqli_real_escape_string($GLOBALS['con'], $statuses) . "', ";
              $query .= " source_json='" . mysqli_real_escape_string($GLOBALS['con'], $sources) . "' ";
              $query .= " WHERE client_id=" . $id;        $this->Query($this->conn, $query);
              return true;
            }
            private function getName($id) {
              try {
                $query = 'SELECT userName FROM users LEFT JOIN leads_assign ON userId=user_id_fk WHERE lead_id_fk=' . $id;
                $result = $this->Query($this->conn, $query);
                $row_count = $this->FetchNum($result);
                if ($row_count > 0) {
                  $name = '';
                  while ($assoc = mysqli_fetch_assoc($result)) {
                    $name .= $assoc['userName'] . ',';
                  }
                  $name = rtrim($name, ',');
                  return $name;
                } else {
                  return 'N/A';
                }
              } catch (Exception $e) {
                error_log($e);
              }
            }
            private function getV3AdminQuery() {
              $query = 'SELECT form_id, lead_json, created_at, fb_form_name  FROM user_leads';
              $query .= ' WHERE active=1 and client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
              $query .= ' ORDER BY form_id DESC  LIMIT 0, 10000';
              return $query;
            }
            private function getV3SalesQuery() {
              $query = ' SELECT form_id, lead_json, created_at, fb_form_name  FROM user_leads';
              $query .= ' LEFT JOIN leads_assign ON user_leads.form_id=leads_assign.lead_id_fk ';
              $query .= ' WHERE active=1 and client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
              $query .= ' AND leads_assign.user_id_fk= ' . $_SESSION['ge_permission']['userId'];
              $query .= ' ORDER BY form_id DESC  LIMIT 0, 10000';
              return $query;
            }
            public function get_leads_V3() {
              if ($_SESSION['ge_permission']['is_admin'] == 1 || $_SESSION['ge_permission']['modules']['view_leads_all'] == 1) {
                $query = $this->getV3AdminQuery();
              } else {
                $query = $this->getV3SalesQuery();
              }
              $result = $this->Query($this->conn, $query);
              $rows = array();
              while ($assoc = mysqli_fetch_assoc($result)) {
                $a = array();
                $a['lead_data'] = $assoc;
                $a['comment'] = $this->getLatestComment($assoc['form_id']);
                if ($_SESSION['ge_permission']['is_admin'] == 1 || $_SESSION['ge_permission']['modules']['view_leads_all'] == 1) {
                  $a['lead_data']['assign_to'] = $this->getName($assoc['form_id']);
                } else {
                  $a['lead_data']['assign_to'] = $_SESSION['ge_permission']['userId'];
                }
                array_push($rows, $a);
              }
              $output['data'] = $rows;
              $Data = str_replace('"{', '{', stripslashes(json_encode($output, true)));
                $data = str_replace('}"', '}', $Data);
                echo $data;
              }
              public function assignLeads() {
                $leads = explode(',', $this->params['leads']);
                $users = explode(',', $this->params['userId']);
                foreach ($leads as $key => $value) {
                  foreach ($users as $key1 => $value1) {
                     // Delete Previous User //
                     $q = " DELETE FROM leads_assign WHERE lead_id_fk=" . $value . " AND user_id_fk=" . $_SESSION['ge_permission']['userId'];
                     $this->Query($this->conn, $q);
                      // Check and assign //
                      $c = "SELECT lead_id_fk FROM leads_assign WHERE lead_id_fk=" . $value . " AND user_id_fk=" . $value1;
                      $result = $this->Query($this->conn, $c);
                      $row_count = $this->FetchNum($result);
                      if ($row_count == 0) {
                        //Assign to New User//
                        $query = " INSERT INTO leads_assign (lead_id_fk, user_id_fk, assigned_by) VALUES (";
                        $query .= $value . "," . $value1 . "," . $_SESSION['ge_permission']['userId'] . ")";
                        $result = $this->Query($this->conn, $query);
                        $udetails = $this->getUserDetails($value1);
                        $lDetails = $this->getLeadDetails($value);
                        $message = 'Hi ' . $udetails['userName'] . ', A lead has been assigned/transferred to you by ' . $_SESSION['ge_permission']['userName'] . '. Please do the follow up, Below are the details.n';
                        $message .= "Name: " . urlencode($lDetails['name']) . "\n";
                        $message .= "Phone: " . urlencode($lDetails['phone']) . "\n";
                        $message .= "Email: " . urlencode($lDetails['email']) . "\n";
                        $message .= "Source: " . urlencode($lDetails['source']);
                        $this->triggerSMS($udetails['phone'], $message);
                      }
                    }
                  }
                  return true;
                }
                private function getUserDetails($id) {
                  $query = 'SELECT userName, userEmail, phone FROM users WHERE userId=' . $id;
                  $result = $this->Query($this->conn, $query);
                  $row_count = $this->FetchNum($result);
                  if ($row_count > 0) {
                    $data = $this->FetchAssoc($result);
                    return $data[0];
                  }
                }
                private function getLeadDetails($id) {
                  $query = 'SELECT lead_json FROM user_leads WHERE form_id=' . $id;
                  $result = $this->Query($this->conn, $query);
                  $row_count = $this->FetchNum($result);
                  if ($row_count > 0) {
                    $data = $this->FetchAssoc($result);
                    return json_decode($data[0]['lead_json'], true);
                  }
                }
                public function get_leads_V3_creports() {
                  $query = 'SELECT form_id, lead_json, created_at, fb_form_name, assign_to FROM user_leads';
                  $query .= ' WHERE active=1 AND client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
                  if (isset($this->params['source'])) {
                    $query .= " AND source IN('" . str_replace(',', "','", $this->params['source']) . "') ";
                  }
                  if (isset($this->params['status'])) {
                    $query .= " AND status IN('" . str_replace(',', "','", $this->params['status']) . "') ";
                  }        if ($_SESSION['ge_permission']['is_admin'] != 1) {
                    $query .= ' AND assign_to IN (0, ' . $_SESSION['ge_permission']['userId'] . ')';
                  }
                  $query .= ' ORDER BY form_id DESC';
                  $result = $this->Query($this->conn, $query);
                  $rows = array();
                  while ($assoc = mysqli_fetch_assoc($result)) {
                    $a = array();
                    $a['lead_data'] = $assoc;
                    $a['comment'] = $this->getLatestComment($assoc['form_id']);
                    if ($assoc['assign_to'] == 0) {
                      $a['lead_data']['assign_to'] = 'N/A';
                    } else {
                      $a['lead_data']['assign_to'] = $this->getName($assoc['assign_to']);
                    }
                    array_push($rows, $a);
                  }
                  $output['data'] = $rows;
                  $Data = str_replace('"{', '{', stripslashes(json_encode($output, true)));
                    $data = str_replace('}"', '}', $Data);
                    echo $data;
                  }
                  private function manageTriggers($lead) {
                    $query = "SELECT * FROM ge_triggers LEFT JOIN users ON ge_triggers.trigger_send_to = users.userId";
                    $query .= " WHERE ge_triggers.client_id_fk=" . $_SESSION['ge_permission']['client_id_fk'];
                    $query .= " AND trigger_status='" . $lead['status'] . "'";
                    //echo $query;
                    $result = $this->Query($this->conn, $query);
                    $row_count = $this->FetchNum($result);
                    if ($row_count > 0) {
                      while ($assoc = mysqli_fetch_assoc($result)) {
                        //print_r($assoc);
                        if ($assoc['trigger_type'] == 1) {
                          $this->triggerSMS($assoc['phone'], $assoc['trigger_content']);
                          if ($assoc['send_to_customer'] == 1) {
                            $this->triggerSMS($lead['phone'], $assoc['trigger_content_customer']);
                          }
                        } else if ($assoc['trigger_type'] == 2) {
                          $this->triggerEmail($assoc['userEmail'], $assoc['trigger_content'], $assoc['trigger_subject']);
                          if ($assoc['send_to_customer'] == 1) {
                            $this->triggerEmail($lead['email'], $assoc['trigger_content_customer'], $assoc['trigger_subject']);
                          }
                        }
                      }
                    }
                  }
                  private function triggerSMS($mobileNumber, $message) {
                    try {//echo $mobileNumber;
                      $authKey = "141494AXw7MW07EuP158a53032";
                      if ($mobileNumber != 0) {
                        $senderId = $_SESSION['ge_permission']['sender_id'];
                        //echo $senderId;
                        $route = "4";
                        $postData = array(
                          'authkey' => $authKey,
                          'mobiles' => $mobileNumber,
                          'message' => $message,
                          'sender' => $senderId,
                          'route' => $route
                        );
                        print_r($postData);
                        $url = "http://text.messagefunda.com/api/sendhttp.php";
                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                          CURLOPT_URL => $url,
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_POST => true,
                          CURLOPT_POSTFIELDS => $postData
                        ));
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        $output = curl_exec($ch);
                        curl_close($ch);
                      }
                    } catch (Exception $exc) {
                      //echo $exc->getTraceAsString();
                    }
                  }
                  private function triggerEmail($to, $content, $subject) {
                    try {
                      $headers = "MIME-Version: 1.0\r\n";
                      $headers .= 'From: ' . ucwords($_SESSION['ge_permission']['userName']) . '<' . $_SESSION['ge_permission']['userEmail'] . ">\r\n";
                      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                      $m = @mail($to, $subject, $content, $headers);
                    } catch (Exception $e) {
                      //echo "ERROR! - 5";
                    }
                  }
                  public function __destruct() {
                    unset($this->params);
                    $this->Close($this->conn);
                  }}
