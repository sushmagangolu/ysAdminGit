<?php

class listController extends CommonController {
    private $params;
    private $date;
    public function __construct($param) {
        $this->params = $param;
        $this->date = date('Y-m-d H:i:s');
        switch ($this->params['list']) {
            case 'SCHOOLS':
                $this->getSchools();
                break;
            case 'USERS':
                $this->getUsers();
                break;
            case 'LEADS':
                $this->getLeads();
                break;
            case 'ENQ':
                $this->getEnquiries();
                break;
            case 'WLIST':
                $this->getWishlist();
                break;
            case 'MYLISTINGS':
                $this->getMyListings();
                break;
            case 'AUTH':
                $this->getUserDetails();
                break;
            case 'ARTICLES':
                $this->getArticles();
                break;
        }
    }

    private function getArticles(){
      $sql = "SELECT * FROM articles";
      $result = mysqli_query($GLOBALS['con'], $sql);
      $rows = array();
      $a = array();
      while ($assoc = mysqli_fetch_assoc($result)) {
          $a['article_title'] = $assoc['article_title'];
          $a['article_created_date'] = $assoc['article_created_date'];
          $a['article_id'] = encrypt_decrypt('encrypt', $assoc['article_id']);
          array_push($rows, $a);
      }
      $output['data'] = $rows;
      $Data = json_encode($output);
      echo $Data;
    }

    private function getWishlist() {
        $sql = "SELECT wish_id, slate_name, wish_created_at FROM school_wishlist";
        $sql .= " LEFT JOIN school_main ON school_wishlist.slate_id_fk=school_main.slate_id";
        $sql .= " WHERE school_wishlist.user_id_fk=" . $_SESSION['user']['user_id'];
        $this->prepareList($sql);
    }

    private function getEnquiries() {
        $query = "SELECT slate_name,lead_created_at,slate_slug FROM school_leads ";
        $query .= " LEFT JOIN school_main ON school_leads.slate_id_fk=school_main.slate_id";
        $query .= " WHERE school_leads.user_id_fk =" . $_SESSION['user']['user_id'];
        $this->prepareList($query);
    }

    private function getLeads() {
        if (isset($_SESSION['user']['schools']) && !empty($_SESSION['user']['schools'])) {
            $query = "SELECT *  FROM school_leads WHERE slate_id_fk IN(" . $_SESSION['user']['schools'] . ")";
            $this->prepareList($query);
        } else {
            $this->prepareEmptyList($query);
        }
    }

    private function getUsers() {
        $query = "SELECT *  FROM users ";
        $this->prepareList($query);
    }

    private function getSchools() {
        $query = "SELECT slate_name,slate_id,verified,city  FROM school_main ";
        $result = mysqli_query($GLOBALS['con'], $query);
        $rows = array();
        $a = array();
        while ($assoc = mysqli_fetch_assoc($result)) {
            $a['name'] = $assoc['slate_name'];
            $a['verified'] = $assoc['verified'];
            $a['city'] = $assoc['city'];
            $a['id'] = encrypt_decrypt('encrypt', $assoc['slate_id']);
            array_push($rows, $a);
        }
        $output['data'] = $rows;
        $Data = json_encode($output);
        echo $Data;
    }

    private function getMyListings() {
        $query = "SELECT slate_name,slate_id,verified,city, address  FROM school_main ";
        $query .= " LEFT JOIN school_info ON slate_id=slate_id_fk";
        $query .= " WHERE user_id_fk=" . $_SESSION['user']['user_id'];
        $result = mysqli_query($GLOBALS['con'], $query);
        $rows = array();
        $a = array();
        while ($assoc = mysqli_fetch_assoc($result)) {
            $a['name'] = $assoc['slate_name'];
            $a['verified'] = $assoc['verified'];
            $a['city'] = $assoc['city'];
            $a['address'] = $assoc['address'];
            $a['id'] = encrypt_decrypt('encrypt', $assoc['slate_id']);
            array_push($rows, $a);
        }
        $output['data'] = $rows;
        $Data = json_encode($output);
        echo $Data;
    }

    private function getUserDetails() {

        try {
            $login_sql = "SELECT * FROM  users WHERE user_id=" . $this->params['userId'];
            $result = $this->Query($this->conn, $login_sql);
            $row_count = $this->FetchNum($result);
            if ($row_count > 0) {
                $data = $this->FetchAssoc($result);
                $_SESSION['user'] = $data[0];
            }
        } catch (Exception $e) {
            error_log($e);
        }
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($GLOBALS['con']);
    }

}
