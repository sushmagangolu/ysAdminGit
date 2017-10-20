<?php

global $con;
$con = $conn;

function update_session_data($email, $password, $login_type, $salt) {
    $login_sql = 'SELECT * FROM  users';
    $login_sql .= ' LEFT JOIN user_access ON users.userId = user_access.user_id_fk';
    $login_sql .= ' LEFT JOIN clients ON users.client_id_fk = clients.client_id';
    $login_sql .= " WHERE userEmail =  '" . mysqli_real_escape_string($GLOBALS['con'], $email) . "' ";
    if (!empty($password)) {
        $npassword = hash('sha512', $salt . $password);
        $login_sql .= " AND password='" . mysqli_real_escape_string($GLOBALS['con'], $npassword) . "'";
    }
    $login_sql .= ' LIMIT 1';
    $query = mysqli_query($GLOBALS['con'], $login_sql);
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['ge_permission'] = $data;
        $_SESSION['ge_permission']['login_type'] = $login_type;
        //profile_pic
        $profile_pic = 'assets/uploads/user.png';
        if ($login_type == 'google') {
            $profile_pic = $data['ge_profile_pic'];
        } elseif ($login_type == 'normal' && !empty($data['profile_pic'])) {
            $profile_pic = 'assets/uploads/' . $data['profile_pic'];
        }
        $_SESSION['ge_permission']['profile_image'] = $profile_pic;

        return 1;
    } else {
        return 0;
    }
}

function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d') {
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while ($current <= $last) {
        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}

function getStartEndDates($sed) {
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

/**
 * create_time_range.
 *
 * @param mixed  $start start time, e.g., 9:30am or 9:30
 * @param mixed  $end   end time, e.g., 5:30pm or 17:30
 * @param string $by    1 hour, 1 mins, 1 secs, etc
 */
function create_time_range($start, $end, $by = '30 mins') {
    $start_time = strtotime($start);
    $end_time = strtotime($end);

    $current = time();
    $add_time = strtotime('+' . $by, $current);
    $diff = $add_time - $current;

    $times = array();
    while ($start_time < $end_time) {
        $times[] = $start_time;
        $start_time += $diff;
    }
    $times[] = $start_time;

    return $times;
}

function alert_time($edate, $before) {
    //  $date = date("Y-m-d H:i:s");
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

function GEencryption($a, $salt) {
    $encrypted_value = hash('sha512', $salt . mysqli_real_escape_string($GLOBALS['con'], $a));

    return $encrypted_value;
}

function getNotificationCount() {
    $events = mysqli_query($GLOBALS['con'], 'select count(*) as total_events from events_calendar where db_start>= NOW() and user_id_fk=' . $_SESSION['ge_permission']['userId'] . ' order by db_start asc');
    $event_data = mysqli_fetch_array($events);
    if ($event_data['total_events'] > 0) {
        return $event_data['total_events'];
    } else {
        return 0;
    }
}

function formatDate($a) {
    return date('Y-m-d H:i:s', strtotime(trim($a)));
}

function encrypt_decrypt($action, $string) {
    // you may change these values to your own
    $secret_key = $GLOBALS['ENCRYPT_KEY'];
    $secret_iv = $GLOBALS['ENCRYPT_KEY'];

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
