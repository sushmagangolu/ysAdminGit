<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of school_controller
 *
 * @author DELL
 */
class schoolController extends CommonController {

    //put your code here
    public function __construct($param) {
        $this->params = $param;
        $this->conn = $GLOBALS['con'];
        switch ($this->params['action']) {
            case 'edit':
                $this->editSchool();
                break;
            case 'add':
                $this->addSchool();
                break;
        }
    }

    private function editSchool() {
        $sid = $this->params['sid'];
        $this->editSchoolMain($sid);
        $this->editSchoolInfo($sid);
        $this->editSchoolFacility($sid);
        $this->saveLogo($sid);
    }

    private function addSchool() {
        $sid = $this->addSchoolMain();
        $this->insertDummy($sid);
        $this->editSchoolMain($sid);
        $this->editSchoolInfo($sid);
        $this->editSchoolFacility($sid);
        $this->saveLogo($sid);
    }

    private function deleteSchool() {
        
    }
    
    private function insertDummy($id){
        // INSERT SCHOOL INFO //
        $sql = "INSERT INTO school_info (slate_id_fk) VALUES (".$id.")";
        $this->Query($this->conn, $sql);
        // INSERT SCHOOL FACILITY //
        $sql = "INSERT INTO school_facility (slate_id_fk) VALUES (".$id.")";
        $this->Query($this->conn, $sql);
    }
    
    private function addSchoolMain() {
        try {
        $sql = "INSERT INTO school_main (slate_name, date_created) VALUES ";
        $sql .= "('" . $this->cleandata($this->params['slate_name']) . "',";
        $sql .= "'" . date('Y-m-d H:i:s') . "')";
        $this->Query($this->conn, $sql);
        $id = $this->getInsertID($this->conn);
        
        return $id;
      } catch (Exception $e) {
        echo $e;
      }
    }

    private function editSchoolMain($id) {
        try {
            $slug = $this->prepareSlug($this->params['slate_name']);
            $admissions = $this->params['admissions_open'] == 'on' ? '1' : '0';
            $verified = $this->params['verified'] == 'on' ? '1' : '0';
            $ins = "UPDATE school_main SET ";
            $ins .= "slate_name='" . $this->cleandata($this->params['slate_name']) . "',";
            $ins .= "slate_slug='" . $this->cleandata($slug) . "',";
            $ins .= "collection_type='" . $this->cleandata($this->params['collection_type']) . "',";
            $ins .= "lat='" . $this->cleandata($this->params['lat']) . "',";
            $ins .= "lng='" . $this->cleandata($this->params['lng']) . "',";
            $ins .= "admissions_open=" . $admissions . ",";
            $ins .= "verified=" . $verified . ",";
            $ins .= "city='" . $this->cleandata($this->params['city']) . "',";
            $ins .= "area='" . $this->cleandata($this->params['area']) . "'";
            $ins .= " WHERE slate_id=" . $id;
            $res = $this->Query($GLOBALS['con'], $ins);
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function editSchoolInfo($id) {
        try {

            $ins = "UPDATE school_info SET ";
            $ins .= "address='" . $this->cleandata($this->params['address']) . "',";
            $ins .= "phone='" . $this->cleandata($this->params['phone']) . "',";
            $ins .= "website='" . $this->cleandata($this->params['website']) . "',";
            $ins .= "email='" . $this->cleandata($this->params['email']) . "',";
            $ins .= "established='" . $this->cleandata($this->params['established']) . "',";
            $ins .= "board='" . $this->cleandata($this->params['board']) . "',";
            $ins .= "medium='" . $this->cleandata($this->params['medium']) . "',";
            $ins .= "grade='" . $this->cleandata($this->params['grade']) . "',";
            $ins .= "school_type='" . $this->cleandata($this->params['school_type']) . "',";
            $ins .= "description='" . $this->cleandata($this->params['description']) . "',";
            $ins .= "directions='" . $this->cleandata($this->params['directions']) . "',";
            $ins .= "admission_procedure='" . $this->cleandata($this->params['admission_procedure']) . "',";
            $ins .= "admission_documents='" . $this->cleandata($this->params['admission_documents']) . "',";
            $ins .= "fee_range='" . $this->cleandata($this->params['fee_range']) . "'";
            $ins .= " WHERE slate_id_fk=" . $id;
            $res = $this->Query($GLOBALS['con'], $ins);
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function editSchoolFacility($id) {

        try {
            $smart_classrooms = $this->params['smart_classrooms'] == 'on' ? '1' : '0';
            $computer_facility = $this->params['computer_facility'] == 'on' ? '1' : '0';
            $cafeteria = $this->params['cafeteria'] == 'on' ? '1' : '0';
            $pre_school = $this->params['pre_school'] == 'on' ? '1' : '0';
            $cctv = $this->params['cctv'] == 'on' ? '1' : '0';
            $fire_escape = $this->params['fire_escape'] == 'on' ? '1' : '0';
            $library = $this->params['library'] == 'on' ? '1' : '0';
            $transport = $this->params['transport'] == 'on' ? '1' : '0';
            $playground = $this->params['playground'] == 'on' ? '1' : '0';
            $medical = $this->params['medical'] == 'on' ? '1' : '0';
            $laboratory = $this->params['laboratory'] == 'on' ? '1' : '0';

            $ins = "UPDATE school_facility SET ";
            $ins .= "books='" . $this->cleandata($this->params['books']) . "',";
            $ins .= "total_boys_student='" . $this->cleandata($this->params['total_boys_student']) . "',";
            $ins .= "total_girls_student='" . $this->cleandata($this->params['total_girls_student']) . "',";
            $ins .= "total_hostel_capacity='" . $this->cleandata($this->params['total_hostel_capacity']) . "',";
            $ins .= "management='" . $this->cleandata($this->params['management']) . "',";
            $ins .= "transport_ownership='" . $this->cleandata($this->params['transport_ownership']) . "',";
            $ins .= "instructors_coaches='" . $this->cleandata($this->params['instructors_coaches']) . "',";
            $ins .= "playground_purpose='" . $this->cleandata($this->params['playground_purpose']) . "',";
            $ins .= "labs='" . $this->cleandata($this->params['labs']) . "',";
            $ins .= "medical_facilites='" . $this->cleandata($this->params['medical_facilites']) . "',";
            $ins .= "sports_facilities='" . $this->cleandata($this->params['sports_facilities']) . "',";
            $ins .= "extra_curricular_activities='" . $this->cleandata($this->params['extra_curricular_activities']) . "',";
            $ins .= "smart_classrooms=" . $smart_classrooms . ",";
            $ins .= "computers_facility=" . $computer_facility . ",";
            $ins .= "cafeteria=" . $cafeteria . ",";
            $ins .= "pre_school=" . $pre_school . ",";
            $ins .= "cctv=" . $cctv . ",";
            $ins .= "fire_escape=" . $fire_escape . ",";
            $ins .= "library=" . $library . ",";
            $ins .= "transport=" . $transport . ",";
            $ins .= "playground=" . $playground . ",";
            $ins .= "medical=" . $medical . ",";
            $ins .= "laboratory=" . $laboratory;
            $ins .= " WHERE slate_id_fk=" . $id;
            $res = $this->Query($GLOBALS['con'], $ins);
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

    private function saveLogo($id) {
        $logo = 'N/A';
        $profile_pic = 'thumb.png';
        $cover_pic = 'profile.png';
        if (!empty($_FILES["logo"]["name"])) {
            $temp = explode(".", $_FILES["logo"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $sourcePath = $_FILES['logo']['tmp_name']; // Storing source path of the file in a variable
            $targetPath = "../../images/schools/" . $newfilename; // Target path where file is to be stored
            move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
            $logo = $newfilename;
        }
        if (!empty($_FILES["profile_pic"]["name"])) {
            $temp = explode(".", $_FILES["profile_pic"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $sourcePath = $_FILES['profile_pic']['tmp_name']; // Storing source path of the file in a variable
            $targetPath = "../../images/schools/" . $newfilename; // Target path where file is to be stored
            move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
            $profile_pic = $newfilename;
        }
        if (!empty($_FILES["cover_pic"]["name"])) {
            $temp = explode(".", $_FILES["cover_pic"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $sourcePath = $_FILES['cover_pic']['tmp_name']; // Storing source path of the file in a variable
            $targetPath = "../../images/schools/" . $newfilename; // Target path where file is to be stored
            move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
            $cover_pic = $newfilename;
        }
        try {
            $ins = "UPDATE school_info SET ";
            $ins .= "logo_tmp='" . $logo . "',";
            $ins .= "image_thumb='" . $profile_pic . "',";
            $ins .= "image_profile='" . $cover_pic . "'";
            $ins .= " WHERE slate_id_fk=" . $id;
            $res = $this->Query($GLOBALS['con'], $ins);
        } catch (Exception $e) {
            error_log($e);
        }
    }

    public function __destruct() {
        
    }

}
