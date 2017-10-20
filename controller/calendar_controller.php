<?php

class calendarController extends CommonController {

    private $params;
    private $conn;
    private $date;
    private $base;
    private $database_date;

    public function __construct($param, $conn) {
        $this->params = $param;
        $this->conn = $conn;
        $this->date = date('Y-m-d H:i:s');
        $this->database_date = date('Y-m-d');

        switch ($this->params['action']) {
            case 'create_event':
                $this->createEvent();
                break;
            case 'edit_event':
                $this->editEvent();
                break;
            case 'delete_event':
                $this->deleteEvent();
                break;
            case 'get_events':
                $this->getEvents();
                break;
            case 'get_notifs':
                $this->getNotifs();
                break;
            case 'get_ncount':
                $this->getNotificationCount();
                break;
        }
    }

    private function getNotificationCount() {
        try {
            $sql = $sql = "SELECT count(*) AS total_events FROM events_calendar ";
            $sql .= " WHERE db_start LIKE '%" . $this->database_date . "%'";
            if ($_SESSION['ge_permission']['userType'] == 'admin') {
                $sql .= " AND client_id_fk=" . $_SESSION['ge_permission']['client_id'];
            } else {
                $sql .= " AND user_id_fk=" . $_SESSION['ge_permission']['userId'];
            }
            $result = $this->Query($this->conn, $sql);
            $event_data = $this->FetchArray($result);
            if ($event_data[0]['total_events'] > 0) {
                echo $event_data[0]['total_events'];
            } else {
                echo 0;
            }
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function getNotifs() {
        try {
            $data = [];
            $data['today'] = $this->getTodayNotifs('TODAY');
            $data['upcoming'] = $this->getTodayNotifs('UPCOMING');
            echo json_encode($data);
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function getTodayNotifs($a) {
        try {
            $sql = "SELECT * FROM events_calendar ";
            if ($a == 'TODAY') {
                $sql .= " WHERE db_start LIKE '%" . $this->database_date . "%'";
            } else {
                $sql .= " WHERE db_start > '".$this->database_date."' AND db_start NOT LIKE  '%" . $this->database_date . "%'";
            }
            if ($_SESSION['ge_permission']['userType'] == 'admin') {
                $sql .= " AND client_id_fk=" . $_SESSION['ge_permission']['client_id'];
            } else {
                $sql .= " AND user_id_fk=" . $_SESSION['ge_permission']['userId'];
            }
            $sql .= " ORDER BY db_start ASC";
            $result = $this->Query($this->conn, $sql);
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

    private function createEvent() {
        try {
            $dates = $this->getStartEndDates($this->params['daterange']);
            $db_start = $dates['start_date_time'];
            $db_end = $dates['end_date_time'];
            $alert_time = $this->alert_time($db_start, $this->params['alert_before']);

            $sql = 'insert into events_calendar (user_id_fk, client_id_fk, event_title, db_start, db_end, alert_before, alert_time, event_created_at) values ';
            $sql .= "('" . mysqli_real_escape_string($this->conn, $_SESSION['ge_permission']['userId']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $_SESSION['ge_permission']['client_id_fk']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['event_title']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $db_start) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $db_end) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['alert_before']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $alert_time) . "',";
            $sql .= "'" . $this->date . "')";
            $res = $this->Query($this->conn, $sql);
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function editEvent() {
        try {
            $dates = $this->getStartEndDates($this->params['daterange_edit']);
            $db_start = $dates['start_date_time'];
            $db_end = $dates['end_date_time'];
            $alert_time = $this->alert_time($db_start, $this->params['alert_before']);

            $sql = 'update events_calendar set ';
            $sql .= " event_title='" . mysqli_real_escape_string($this->conn, $this->params['event_title']) . "',";
            $sql .= " db_start='" . mysqli_real_escape_string($this->conn, $db_start) . "',";
            $sql .= " db_end='" . mysqli_real_escape_string($this->conn, $db_end) . "',";
            $sql .= " alert_before='" . mysqli_real_escape_string($this->conn, $this->params['alert_before']) . "', ";
            $sql .= " alert_time='" . mysqli_real_escape_string($this->conn, $alert_time) . "' ";
            $sql .= " where event_id='" . mysqli_real_escape_string($this->conn, $this->params['event_id']) . "' ";

            $res = mysqli_query($this->conn, $sql);
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function deleteEvent() {
        try {
            $delete_event = 'DELETE FROM events_calendar WHERE event_id=' . $this->params['event_id'];
            $res = mysqli_query($this->conn, $delete_event);
            echo $this->getNotificationCount();
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function getEvents() {
        try {

            if ($_SESSION['ge_permission']['userType'] == 'admin') {
                $sql = 'SELECT * FROM events_calendar WHERE client_id_fk=' . $_SESSION['ge_permission']['client_id'];
            } else {
                $sql = 'SELECT * FROM events_calendar WHERE user_id_fk=' . $_SESSION['ge_permission']['userId'];
            }

            $res = mysqli_query($this->conn, $sql);
            $events = array();
            while ($row = mysqli_fetch_assoc($res)) {
                $e = array();
                $e['id'] = $row['event_id'];
                $e['lead_id'] = $row['lead_id_fk'];
                $e['title'] = $row['event_title'];
                $e['start'] = str_replace(' ', 'T', trim($row['db_start']));
                $e['end'] = str_replace(' ', 'T', trim($row['db_end']));
                $e['allDay'] = false;
                $e['alert_before'] = $row['alert_before'];
                if ($row['lead_id_fk'] == 0) {
                    $e['className'] = 'bg-primary tooltip-html';
                } else {
                    $e['className'] = 'bg-success tooltip-html';
                }


                // Merge the event array into the return array
                array_push($events, $e);
            }
            $Data = json_encode($events);
            echo $Data;
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function getStartEndDates($sed) {
        $dates = array();
        $explode = explode('TO', $sed);
        $sDate = strtotime(trim($explode[0]));
        $eDate = strtotime(trim($explode[1]));
        if ($sDate == $eDate) {
            $eDate = strtotime('+30 minutes', $eDate);
        }

        $dates['start_date_time'] = date('Y-m-d H:i:s', $sDate);
        $dates['end_date_time'] = date('Y-m-d H:i:s', $eDate);

        return $dates;
    }

    private function alert_time($edate, $before) {
        switch ($before) {
            case '5 mins':
                $min = 5;
                break;
            case '10 mins':
                $min = 10;
                break;
            case '15 mins':
                $min = 15;
                break;
            default:
            case '30 mins':
                $min = 30;
                break;
            case '1 hour':
                $min = 60;
                break;
            case '2 hour':
                $min = 120;
                break;
            case '1 day':
                $min = 1440;
                break;
            case '2 day':
                $min = 2880;
                break;
        }
        $time = strtotime($edate);
        $time = $time - ($min * 60);

        return date('Y-m-d H:i:s', $time);
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
