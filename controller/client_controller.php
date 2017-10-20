<?php

class clientController extends CommonController {

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
            case 'ins_client':
                $this->insClient();
                break;
            case 'edit_client':
                $this->editClient();
                break;
            case 'delete_client':
                $this->deleteClient();
                break;
            case 'sms_settings':
                $this->updateSMSSettings();
                break;
        }
    }

    private function updateSMSSettings() {
        $sql = 'UPDATE clients SET ';
        $sql .= " sender_id='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['sender_id']) . "',";
        $sql .= " promotional_sms='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['promo_sms'] == 'on' ? 1 : 0) . "',";
        $sql .= " promotional_sms_text='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['promo_text']) . "' ";
        $sql .= " WHERE client_id='" . $_SESSION['ge_permission']['client_id_fk'] . "' ";
        $this->Query($this->conn, $sql);
        echo 1;
    }

    private function insClient() {
        $checkEmail = $this->checkEmailExists();
        if ($checkEmail) {
            $this->newClientId = $this->createClient();
            $this->newRoleId = $this->createAdminRole();
            $this->branchId = $this->createMainBranch();
            $this->userId = $this->createPrimaryUser();
            $this->createPrimaryUserOptions();
            $this->sendVerificationEmail();
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    private function createPrimaryUserOptions() {
        $sql = "INSERT INTO user_options(user_id_fk, client_id_fk, role_id_fk, branch_id_fk) VALUES (";
        $sql .= $this->userId . ", ";
        $sql .= $this->newClientId . ", ";
        $sql .= $this->newRoleId . ", ";
        $sql .= $this->branchId . ")";
        $this->Query($this->conn, $sql);
        return true;
    }

    private function checkEmailExists() {
        return true;
        /* $sql = "SELECT userEmail FROM users WHERE userEmail='" . $this->params['client_email'] . "'";
          $result = $this->Query($this->conn, $sql);
          $row_count = $this->FetchNum($result);
          if ($row_count > 0) {
          return false;
          } else {
          return true;
          } */
    }

    private function createClient() {
        $default_form = '[
	{
		"type": "text",
		"required": true,
		"label": "Name",
		"subtype": "text",
		"placeholder": "Enter name",
		"className": "form-control",
		"name": "name",
		"access": true
	},
	{
		"type": "text",
		"required": true,
		"label": "Email",
		"subtype": "text",
		"placeholder": "Enter email",
		"className": "form-control",
		"name": "email",
		"access": true
	},
	{
		"type": "text",
		"required": true,
		"label": "Phone",
		"subtype": "text",
		"placeholder": "Enter phone",
		"className": "form-control",
		"name": "phone",
		"access": true
	},
	{
		"type": "select",
		"label": "Source",
		"className": "form-control",
		"name": "source",
		"values": [
			{
				"label": "Walkin",
				"value": "Walkin",
				"selected": true
			},
			{
				"label": "Phone",
				"value": "Phone"
			},
			{
				"label": "Website",
				"value": "Website"
			},
			{
				"label": "Online Ads",
				"value": "Online Ads"
			},
			{
				"label": "Facebook",
				"value": "Facebook"
			}
		]
	},
	{
		"type": "select",
		"label": "Status",
		"className": "form-control",
		"name": "status",
		"values": [
			{
				"label": "New Lead",
				"value": "New Lead",
				"selected": true
			},
      {
				"label": "Converted",
				"value": "Converted"
			}
		]
	}
]';
        $source_json = '[{"source":"Walkin"},{"source":"Phone"},{"source":"Website"},{"source":"Online Ads"},{"source":"Facebook"}]';
        $status_json = '[{"status":"New Lead"},{"status":"Converted"}]';
        $license = $this->generateLicense();
        $sql = "INSERT INTO clients (client_name, access_code, client_phone, client_email, client_address, primary_contact_name, primary_contact_phone, client_image, date_created, client_website, source_json, status_json, facebook_page_id, lead_json) VALUES ";
        $sql .= "('" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_name']) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $license) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_phone']) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_email']) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_address']) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['primary_contact_name']) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['primary_contact_phone']) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_image']) . "',";
        $sql .= "'" . $this->date . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_website']) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $source_json) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $status_json) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['fbPageId']) . "',";
        $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $default_form) . "')";
        $this->Query($this->conn, $sql);
        $insertId = $this->getInsertID($this->conn);
        return $insertId;
    }

    private function createAdminRole() {
        try {
            $perms = '{"mod_users":1,"mod_leads":1,"view_leads":1,"campaign_email":1,"import":1,"trash":1,"calendar":1,"notifications":1,"facebook":1,"google":1,"del_leads":1,"export":1,"view_leads_all":1}';
            $sql = "INSERT INTO user_roles (role_name, role_permissions, is_admin, is_sales, client_id_fk) VALUES ";
            $sql .= "('Administrator',";
            $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $perms) . "',";
            $sql .= "1,";
            $sql .= "0,";
            $sql .= "'" . $this->newClientId . "')";
            $this->Query($this->conn, $sql);
            $insertId = $this->getInsertID($this->conn);
            return $insertId;
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function createMainBranch() {
        try {
            $sql = "INSERT INTO branches (client_id_fk, branch_name, is_main_branch, branch_created_by) VALUES ";
            $sql .= "(" . $this->newClientId . ",";
            $sql .= "'MAIN BRANCH',";
            $sql .= "1,";
            $sql .= "'" . $this->newClientId . "')";
            $this->Query($this->conn, $sql);
            $insertId = $this->getInsertID($this->conn);
            return $insertId;
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function createPrimaryUser() {
        try {
            $lead_cols = '[
          {"title":"<input type=checkbox value=CheckAll name=chkall id=checkAll>","data":null},
          {"title":"Name","data":"lead_data.lead_json.name","defaultContent":"---","className":"parent_name"},
          {"title":"Email","data":"lead_data.lead_json.email","defaultContent":"---"},
          {"title":"Phone","data":"lead_data.lead_json.phone","defaultContent":"---"},
          {"title":"Source","data":"lead_data.lead_json.source","defaultContent":"---"},
          {"title":"Status","data":"lead_data.lead_json.status","defaultContent":"---"},
          {"title":"Assigned To","data":"lead_data.assign_to","defaultContent":"---"},
          {"title":"Comment","data":"comment.remark","defaultContent":"---","width":"15%"},
          {"title":"Date","data":"lead_data.created_at","defaultContent":"---"}
        ]';
            $sql = "INSERT INTO users (client_id_fk, role_id_fk, userName, password, phone, lead_columns, userEmail) VALUES ";
            $sql .= "('" . $this->newClientId . "',";
            $sql .= "'" . $this->newRoleId . "',";
            $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_name']) . "',";
            $sql .= "'dontaskmethat',";
            $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['primary_contact_phone']) . "',";
            $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $lead_cols) . "',";
            $sql .= "'" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_email']) . "')";
            $this->Query($this->conn, $sql);
            $insertId = $this->getInsertID($this->conn);
            return $insertId;
        } catch (Exception $e) {
            echo $e;
        }
    }

    private function sendVerificationEmail() {
        //Send Email to reset the password//
        $passwordkey = hash('sha512', $GLOBALS['salt'] . mysqli_real_escape_string($this->conn, $this->params['client_email']));
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
                            <td class="alert alert-warning" style="font-family: tahoma; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: orange; margin: 0; padding: 20px;"
                                align="center" bgcolor="teal" valign="top">
                                Reset Your Password
                            </td>
                        </tr>
                        <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-wrap" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                        Dear ' . $this->params['client_name'] . ',
                                    </td>
                                </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            ' . $_SESSION['ge_permission']['userName'] . ' has granted you access to Growth Eye. You can set your password by clicking below.
                                        </td>
                                    </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px; text-align: center;" valign="top">
                                            <a href="' . $pwrurl . '" class="btn-primary" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #188ae2; margin: 0; border-color: #188ae2; border-style: solid; border-width: 10px 20px;">Set My Password</a>
                                        </td>
                                    </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            Growth Eye is an online platform that will help you manage all your leads and communicate with them. We have more exciting features coming soon. Meanwhile, we hope our platform will help you grow your business :)
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
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= 'From: Growth Eye<' . $_SESSION['ge_permission']['userEmail'] . ">\r\n";
        $headers .= "Content-type: text/html\r\n";
        @mail($this->params['client_email'], 'Growth Eye access has been granted', $email_message, $headers);
        return true;
    }

    private function checkEmailExistsWhileEdit() {
        return true;
        /*$sql = "SELECT client_email FROM clients WHERE client_email='" . $this->params['client_email'] . "'";
        $sql .= " AND client_id !='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_id']) . "' ";

        $result = $this->Query($this->conn, $sql);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            return false;
        } else {
            return true;
        }*/
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
            if (!empty($_REQUEST['client_image'])) {
                $sql .= " client_image='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_image']) . "', ";
            }
            $sql .= " primary_contact_phone='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['primary_contact_phone']) . "' ";
            $sql .= " WHERE client_id='" . mysqli_real_escape_string($GLOBALS['con'], $this->params['client_id']) . "' ";
            $this->Query($this->conn, $sql);
            echo 1;
        } else {
            echo 0;
        }
    }

    private function deleteClient() {
        $this->Query($GLOBALS['con'], 'UPDATE clients SET client_active=0 WHERE client_id=' . $this->params['client_id']);
        $this->Query($GLOBALS['con'], 'UPDATE users  SET user_active=0 WHERE client_id_fk=' . $this->params['client_id']);
        return true;
    }

    private function generateLicense() {
        $key = implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4));
        return $key;
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
