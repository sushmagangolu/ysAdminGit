<?php

class CommonController {

    public function __construct() {

    }

    public function Close($conn) {
        mysqli_close($conn);
    }

    public function FetchRow($query) {
        $rows = mysqli_fetch_row($query);
        return $rows;
    }

    public function FetchArray($query) {
        $rows = array();
        while ($assoc = mysqli_fetch_array($query)) {
            $rows[] = $assoc;
        }
        return $rows;
    }

    public function FetchAssoc($query) {
        $rows = array();
        while ($assoc = mysqli_fetch_assoc($query)) {
            $rows[] = $assoc;
        }
        return $rows;
    }

    public function FetchNum($query) {
        $num = mysqli_num_rows($query);
        return $num;
    }

    public function Query($conn, $sql) {
        $query = mysqli_query($conn, $sql);
        return $query;
    }

    public function getInsertID($conn) {
        $insertid = mysqli_insert_id($conn);
        return $insertid;
    }

    public function BufferQuery($sql) {
        $query = mysql_unbuffered_query($sql) or die(mysql_error());
        return $query;
    }

    public function AffectedRows($conn) {
        $arows = mysqli_affected_rows($conn);
        return $arows;
    }

    public function getSQL($query) {
        return $query;
    }

    public function prepareList($query) {
        $result = $this->Query($GLOBALS['con'], $query);
        $rows = $this->FetchAssoc($result);
        $output['data'] = $rows;
        $Data = json_encode($output);
        echo $Data;
    }

    public function prepareEmptyList() {
        $rows = array();
        $output['data'] = $rows;
        $Data = json_encode($output);
        echo $Data;
    }
    public function cleandata($data) {
        $fdata = mysqli_real_escape_string($GLOBALS['con'], trim(stripslashes($data)));
        return $fdata;
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


    public function __destruct() {

    }


}
