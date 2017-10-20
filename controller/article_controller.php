<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of blogController
 *
 * @author DELL
 */
session_start();
class blogController extends CommonController {

    //put your code here
    public function __construct($param) {
        $this->params = $param;
        $this->conn = $GLOBALS['con'];
        switch ($this->params['action']) {
            case 'edit':
                $this->editBlog();
                break;
            case 'add':
                $this->addBlog();
                break;
        }
    }

    private function editBlog() {
          $sid = encrypt_decrypt('decrypt', $this->params['id']);
          $this->editBlogMain($sid);
          echo 'Success';
    }

    private function addBlog() {
        $sid = $this->addBlogMain();
        $this->editBlogMain($sid);
        echo 'Success';
    }

    private function deleteSchool() {

    }

    private function addBlogMain() {
        try {
        $sql = "INSERT INTO articles (article_title, article_created_date) VALUES ";
        $sql .= "('" . $this->cleandata($this->params['article_title']) . "',";
        $sql .= "'" . date('Y-m-d H:i:s') . "')";
        $this->Query($this->conn, $sql);
        $id = $this->getInsertID($this->conn);
        return $id;
      } catch (Exception $e) {
        echo $e;
      }
    }

    private function editBlogMain($id) {
        try {
            $slug = $this->prepareSlug($this->params['article_title']);
            $ins = "UPDATE articles SET ";
            $ins .= "article_title='" . $this->cleandata($this->params['article_title']) . "',";
            $ins .= "article_slug='" . $slug . "',";
            $ins .= "article_content='" . $this->cleandata($this->params['article_content']) . "',";
            $ins .= "article_status=2,";
            $ins .= "user_id_fk=".$_SESSION['user']['user_id'];
            $ins .= " WHERE article_id=" .  $id;
            $res = $this->Query($GLOBALS['con'], $ins);
            if (!empty($_FILES["featured_image"]["name"])) {
              $this->saveFeaturedImage($id);
            }
        } catch (Exception $e) {
            error_log($e);
        }
    }

      private function saveFeaturedImage($id) {
        try {
          if (!empty($_FILES["featured_image"]["name"])) {
              $temp = explode(".", $_FILES["featured_image"]["name"]);
              $newfilename = round(microtime(true)) . '.' . end($temp);
              $sourcePath = $_FILES['featured_image']['tmp_name']; // Storing source path of the file in a variable
              $targetPath = "../../images/blog/" . $newfilename; // Target path where file is to be stored
              move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
              $logo = $newfilename;
              $ins = "UPDATE articles SET ";
              $ins .= "article_featured_image='" . $logo . "'";
              $ins .= " WHERE article_id=" .  $id;
              $res = $this->Query($GLOBALS['con'], $ins);
          }
        } catch (Exception $e) {
          error_log($e);
        }
      }

    private function prepareSlug($name) {
        $name = str_replace("'", "", $name);
        $name = str_replace(".", "", $name);
        $name = str_replace(" ", "-", $name);
        return strtolower($name);
    }

    public function __destruct() {

    }


}
