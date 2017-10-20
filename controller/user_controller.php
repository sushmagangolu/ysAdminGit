<?php

class userController extends CommonController {

    private $params;
    private $conn;
    private $date;
    private $base;

    public function __construct($param, $conn) {
        $this->params = $param;
        $this->conn = $conn;
        $this->date = date('Y-m-d H:i:s');
        $this->base = $_SERVER['HTTP_HOST'] . "/";
        switch ($this->params['act']) {
            case 'ins_user':
                $this->insUser();
                break;
            case 'edit_user':
                $this->editUser();
                break;
            case 'delete_user':
                $this->deleteUser();
                break;
            case 'general_settings':
                $this->generalSettings();
                break;
            case 'getUserDetails':
                $this->getUserDetails();
                break;
            case 'edit_user_profile':
                $this->editUserProfile();
                break;
            case 'get_all_users':
                $this->getAllUsers();
                break;
            case 'lead_col_settings':
                $this->leadColSettings();
                break;
            case 'ins_role':
                $this->insRole();
                break;
            case 'edit_role':
                $this->editRole();
                break;
            case 'pause_user':
                $this->pauseUser();
                break;
            case 'send_verify_email':
                $this->sendVerificationEmail();
                break;
        }
    }

    private function getAllUsers() {
        try {
            $query = "SELECT userId, userName, phone, userEmail, client_name, user_active, userType, email_notification, sms_notification, email_reminders FROM users LEFT JOIN clients ON users.client_id_fk = clients.client_id";
            $query .= " ORDER BY client_name ASC";
            $result = $this->Query($this->conn, $query);
            $rows = array();
            while ($assoc = mysqli_fetch_assoc($result)) {
                $a = array();
                $a['user_data'] = $assoc;
                array_push($rows, $a);
            }
            $output['data'] = $rows;
            $Data = str_replace('"{', '{', stripslashes(json_encode($output, true)));
            $data = str_replace('}"', '}', $Data);
            echo $data;
        } catch (Exception $e) {
            
        }
    }

    private function editUserProfile() {
        try {
            if (!empty($_FILES["profile_pic"]["name"])) {
                $temp = explode(".", $_FILES["profile_pic"]["name"]);
                $newfilename = round(microtime(true)) . '.' . end($temp);
                $sourcePath = $_FILES['profile_pic']['tmp_name']; // Storing source path of the file in a variable
                $targetPath = "../assets/uploads/" . $newfilename; // Target path where file is to be stored
                move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
                $ou_profile = $newfilename;
            }
            $sql = 'UPDATE users SET ';
            $sql .= " userName='" . mysqli_real_escape_string($this->conn, $this->params['userName']) . "',";
            $sql .= " phone='" . mysqli_real_escape_string($this->conn, $this->params['phone']) . "',";
            $sql .= " role_id_fk='" . mysqli_real_escape_string($this->conn, $this->params['roles_ge']) . "'";
            if (isset($ou_profile)) {
                $sql .= " ,profile_pic='" . mysqli_real_escape_string($GLOBALS['con'], $ou_profile) . "' ";
            }
            $sql .= " WHERE userId='" . mysqli_real_escape_string($this->conn, $this->params['userId']) . "' ";
            echo $sql;
            $this->Query($this->conn, $sql);
            $this->deleteUserOptions($this->params['userId']);
            $this->insertUserOptions($this->params['userId']);
            return true;
        } catch (Exception $e) {
            
        }
    }

    private function getUserDetails() {
        try {
            $sql = "SELECT * FROM users WHERE userId=" . $this->params['userId'];
            $result = $this->Query($this->conn, $sql);
            $rows = $this->FetchAssoc($result);
            $data = array();
            $data['user'] = $rows[0];
            $data['branches'] = $this->getBranchDetails();
            echo json_encode($data);
        } catch (Exception $e) {
            
        }
    }

    private function getBranchDetails() {
        try {
            $sql = "SELECT branch_id_fk FROM user_options WHERE user_id_fk=" . $this->params['userId'] . " AND client_id_fk=" . $_SESSION['ge_permission']['client_id_fk'];
            $result = $this->Query($this->conn, $sql);
            $rows = $this->FetchAssoc($result);
            return $rows;
        } catch (Exception $e) {
            
        }
    }

    private function insUser() {
        $checkEmail = $this->checkEmailExists();
        if ($checkEmail) {
            $clientId = $this->createUser();
            $this->sendVerificationEmail();
            echo 1;
        } else {
            echo 'Email Already Exists! Please try to use a different one.';
        }
        exit;
    }

    private function checkEmailExists() {
        $sql = "SELECT userEmail FROM users WHERE userEmail='" . $this->params['userEmail'] . "'";
        $sql .= " AND client_id_fk ='" . mysqli_real_escape_string($this->conn, $_SESSION['ge_permission']['client_id_fk']) . "' ";
        $result = $this->Query($this->conn, $sql);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            return false;
        } else {
            return true;
        }
    }

    private function generalSettings() {
        try {
            $sql = 'UPDATE users SET ';
            $sql .= " email_reminders='" . mysqli_real_escape_string($this->conn, $this->params['email_reminders'] == 'on' ? 1 : 0) . "',";
            $sql .= " email_notification='" . mysqli_real_escape_string($this->conn, $this->params['email_notification'] == 'on' ? 1 : 0) . "',";
            $sql .= " sms_notification='" . mysqli_real_escape_string($this->conn, $this->params['sms_notification'] == 'on' ? 1 : 0) . "'";
            $sql .= " WHERE userId='" . mysqli_real_escape_string($this->conn, $this->params['userId']) . "' ";
            $this->Query($this->conn, $sql);
            return true;
        } catch (Exception $e) {
            
        }
    }

    private function createUser() {
        try {
            $sql = "INSERT INTO users (client_id_fk, userName, password, userEmail, phone, role_id_fk) VALUES ";
            $sql .= "('" . $_SESSION['ge_permission']['client_id_fk'] . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['userName']) . "',";
            $sql .= "'dontaskmethat',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['userEmail']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['userPhone']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['roles_ge']) . "')";
            $this->Query($this->conn, $sql);
            $id = $this->getInsertID($this->conn);
            $this->insertUserOptions($id);
            return true;
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function insertUserOptions($id) {
        if (!empty($this->params['br_ge'])) {
            $branches = explode(',', $this->params['br_ge']);
            print_r($branches);
            foreach ($branches as $key => $value) {
                $sql = "INSERT INTO user_options(user_id_fk, client_id_fk, role_id_fk, branch_id_fk) VALUES (";
                $sql .= $id . ", ";
                $sql .= $_SESSION['ge_permission']['client_id_fk'] . ", ";
                $sql .= $this->params['roles_ge'] . ", ";
                $sql .= $value . ")";
                echo $sql;
                $this->Query($this->conn, $sql);
            }
        }
//        } else {
//            $sql = "INSERT INTO user_options(user_id_fk, client_id_fk, role_id_fk) VALUES (";
//            $sql .= $id . ", ";
//            $sql .= $_SESSION['ge_permission']['client_id_fk'] . ", ";
//            $sql .= $this->params['roles_ge'] . ")";
//            $this->Query($this->conn, $sql);
//        }
    }

    private function deleteUserOptions($id) {
        try {
            $sql = "DELETE FROM user_options WHERE user_id_fk=" . $id;
            echo $sql;
            $this->Query($this->conn, $sql);
            return true;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function sendVerificationEmail() {
        //Send Email to reset the password//
        $passwordkey = hash('sha512', $GLOBALS['salt'] . mysqli_real_escape_string($this->conn, $this->params['userEmail']));
        $pwrurl = $this->base . 'reset_password.php?q=' . $passwordkey;
        $email_message = '';
        $email_message .= '<table class="body-wrap" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
        <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
            <td style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
            <td class="container" width="600" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
                valign="top">
                <div class="content" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;"
                        bgcolor="#fff">
                        <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="alert alert-warning" style="font-family: tahoma; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: teal; margin: 0; padding: 20px;"
                                align="center" bgcolor="teal" valign="top">
                                Reset Your Password
                            </td>
                        </tr>
                        <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-wrap" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                        Dear ' . $this->params['userName'] . ',
                                    </td>
                                </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            ' . $_SESSION['ge_permission']['userName'] . ' has granted you access to Growth Eye. You can set your password by clicking below.
                                        </td>
                                    </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px; text-align: center;" valign="top">
                                            <a href="' . $pwrurl . '" class="btn-primary" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #f5707a; margin: 0; border-color: #f5707a; border-style: solid; border-width: 10px 20px;">Set My Password</a>
                                        </td>
                                    </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            Growth Eye is an online platform that will help you manage all your leads and communicate with them. We have more exciting features coming soon.Meanwhile, we hope our platform will help you grow your business :)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
        </tr>
    </table>';
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= 'From: ' . $_SESSION['ge_permission']['client_name'] . '<' . $_SESSION['ge_permission']['userEmail'] . ">\r\n";
        $headers .= "Content-type: text/html\r\n";
        @mail($this->params['userEmail'], 'GrowthEye access has been granted', $email_message, $headers);
        return true;
    }

    private function editUser() {
        try {
            $sql = 'UPDATE users SET ';
            $sql .= " userName='" . mysqli_real_escape_string($this->conn, $this->params['userName']) . "', ";
            $sql .= " phone='" . mysqli_real_escape_string($this->conn, $this->params['userPhone']) . "', ";
            $sql .= " role_id_fk='" . mysqli_real_escape_string($this->conn, $this->params['roles_ge']) . "' ";
            $sql .= " WHERE userId='" . mysqli_real_escape_string($this->conn, $this->params['user_id']) . "' ";
            $sql .= " AND client_id_fk ='" . mysqli_real_escape_string($this->conn, $_SESSION['ge_permission']['client_id_fk']) . "' ";
            $this->Query($this->conn, $sql);
            $this->deleteUserOptions($this->params['user_id']);
            $this->insertUserOptions($this->params['user_id']);
            echo 1;
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function getPermissions() {
        $record = array();
        $record['mod_users'] = $this->params['mod_users'] == 'on' ? 1 : 0;
        $record['mod_leads'] = $this->params['mod_leads'] == 'on' ? 1 : 0;
        $record['campaign_email'] = $this->params['campaign_email'] == 'on' ? 1 : 0;
        $record['import'] = $this->params['import'] == 'on' ? 1 : 0;
        $record['trash'] = $this->params['trash'] == 'on' ? 1 : 0;
        $record['calendar'] = $this->params['calendar'] == 'on' ? 1 : 0;
        $record['notifications'] = $this->params['notifications'] == 'on' ? 1 : 0;
        $record['facebook'] = $this->params['facebook'] == 'on' ? 1 : 0;
        $record['google'] = $this->params['google'] == 'on' ? 1 : 0;
        $record['email_notification'] = $this->params['email_notification'] == 'on' ? 1 : 0;
        $record['sms_notification'] = $this->params['sms_notification'] == 'on' ? 1 : 0;
        return json_encode($record);
    }

    private function leadColSettings() {
        try {
            $cols = json_decode($_SESSION['ge_permission']['lead_json'], true);
            $col = array(
                array(
                    'title' => '<input type=checkbox value=CheckAll name=chkall id=checkAll>',
                    'data' => null
                ),
                array(
                    'title' => 'Name',
                    'data' => 'lead_data.lead_json.name',
                    'defaultContent' => '---',
                    'className' => 'parent_name'
                )
            );

            foreach ($cols as $key => $value) {
                if ($value['name'] != 'created_at') {
                    if ($this->params['col_' . $value['name']] == 'on') {
                        $a = array();
                        $a['title'] = $value['label'];
                        $a['data'] = 'lead_data.lead_json.' . $value['name'];
                        $a['defaultContent'] = '---';
                        array_push($col, $a);
                    }
                }
            }

            $fb_form_name['title'] = 'FB Form';
            $fb_form_name['data'] = 'lead_data.fb_form_name';
            $fb_form_name['defaultContent'] = '---';
            $fb_form_name['width'] = '15%';
            array_push($col, $fb_form_name);

            $assign_to['title'] = 'Assigned To';
            $assign_to['data'] = 'lead_data.assign_to';
            $assign_to['defaultContent'] = '---';
            $assign_to['width'] = '15%';
            array_push($col, $assign_to);

            $comment['title'] = 'Comment';
            $comment['data'] = 'comment.remark';
            $comment['defaultContent'] = '---';
            $comment['width'] = '15%';
            array_push($col, $comment);

            $date_col['title'] = 'Date';
            $date_col['data'] = 'lead_data.created_at';
            $date_col['defaultContent'] = '---';
            array_push($col, $date_col);

            //echo json_encode($col);
            $sql = 'UPDATE users SET ';
            $sql .= " lead_columns='" . mysqli_real_escape_string($this->conn, json_encode($col)) . "'";
            $sql .= " WHERE userId='" . mysqli_real_escape_string($this->conn, $this->params['userId']) . "' ";
            $this->Query($this->conn, $sql);
            $_SESSION['ge_permission']['lead_columns'] = json_encode($col);
            echo json_encode($col);
            //return true;
        } catch (Exception $e) {
            
        }
    }

    private function deleteUser() {
        $this->Query($this->conn, 'UPDATE users  SET user_active=0 WHERE userId=' . $this->params['user_id']);
        return true;
    }

    private function pauseUser() {
        $this->Query($this->conn, 'UPDATE users  SET user_pause=' . $this->params['pause'] . ' WHERE userId=' . $this->params['user_id']);
        return true;
    }

    private function getRolePermissions() {
        $record = array();
        $record['mod_users'] = $this->params['mod_users'] == 'on' ? 1 : 0;
        $record['mod_leads'] = $this->params['mod_leads'] == 'on' ? 1 : 0;
        $record['view_leads'] = $this->params['view_leads'] == 'on' ? 1 : 0;
        $record['campaign_email'] = $this->params['campaign_email'] == 'on' ? 1 : 0;
        $record['import'] = $this->params['import'] == 'on' ? 1 : 0;
        $record['trash'] = $this->params['trash'] == 'on' ? 1 : 0;
        $record['calendar'] = $this->params['calendar'] == 'on' ? 1 : 0;
        $record['notifications'] = $this->params['notifications'] == 'on' ? 1 : 0;
        $record['facebook'] = $this->params['facebook'] == 'on' ? 1 : 0;
        $record['google'] = $this->params['google'] == 'on' ? 1 : 0;
        $record['del_leads'] = $this->params['del_leads'] == 'on' ? 1 : 0;
        $record['export'] = $this->params['export'] == 'on' ? 1 : 0;
        $record['view_leads_all'] = $this->params['view_leads_all'] == 'on' ? 1 : 0;
        $record['sms_triggers'] = $this->params['sms_triggers'] == 'on' ? 1 : 0;
        $record['email_triggers'] = $this->params['email_triggers'] == 'on' ? 1 : 0;
        $record['manage_branches'] = $this->params['manage_branches'] == 'on' ? 1 : 0;
        $record['manage_roles'] = $this->params['manage_roles'] == 'on' ? 1 : 0;
        $record['assign'] = $this->params['assign'] == 'on' ? 1 : 0;
        return json_encode($record);
    }

    private function insRole() {
        try {
            $userAccess = $this->getRolePermissions();
            $isAdmin = $this->params['checkall'] == 'on' ? '1' : '0';
            $isSales = $this->params['is_sales'] == 'on' ? '1' : '0';
            $sql = "INSERT INTO user_roles (client_id_fk, role_name, role_permissions, is_admin, is_sales, role_created_at) VALUES ";
            $sql .= "('" . $_SESSION['ge_permission']['client_id_fk'] . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['roleName']) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $userAccess) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $isAdmin) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $isSales) . "',";
            $sql .= "'" . mysqli_real_escape_string($this->conn, $this->date) . "')";
            $this->Query($this->conn, $sql);
            echo 1;
        } catch (Exception $e) {
            error_log($e);
        }
    }

    private function editRole() {
        try {
            $userAccess = $this->getRolePermissions();
            $isAdmin = $this->params['checkall'] == 'on' ? '1' : '0';
            $isSales = $this->params['is_sales'] == 'on' ? '1' : '0';
            $sql = 'UPDATE user_roles SET ';
            $sql .= " role_name='" . mysqli_real_escape_string($this->conn, $this->params['roleName']) . "', ";
            $sql .= " is_admin='" . mysqli_real_escape_string($this->conn, $isAdmin) . "', ";
            $sql .= " is_sales='" . mysqli_real_escape_string($this->conn, $isSales) . "', ";
            $sql .= " role_permissions='" . mysqli_real_escape_string($this->conn, $userAccess) . "' ";
            $sql .= " WHERE role_id='" . mysqli_real_escape_string($this->conn, $this->params['role_id']) . "' ";
            $sql .= " AND client_id_fk ='" . mysqli_real_escape_string($this->conn, $_SESSION['ge_permission']['client_id_fk']) . "' ";
            $this->Query($this->conn, $sql);
            $affected = $this->AffectedRows($this->conn);
            if ($affected > 0) {
                echo 1;
            } else {
                echo 0;
            }
        } catch (Exception $e) {
            error_log($e);
        }
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
