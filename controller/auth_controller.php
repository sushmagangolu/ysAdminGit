<?php
session_start();
require '../phpmailer/PHPMailerAutoload.php';
class authController extends CommonController {
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
            case 'fp_user':
                $this->fp_user();
                break;
            case 'cp_user':
                $this->cp_user();
                break;
            case 'login':
                $this->login();
                break;
            case 'verify':
                $this->verifyEmail();
                break;
            case 'rp_user':
                $this->rp_user();
                break;
        }
    }

    private function rp_user() {
      $checkEmail = $this->checkEmailHashExists();
      if($checkEmail) {
        echo 0;
      } else {
        $hashpassword = hash('sha512', $GLOBALS['salt'] . $this->params['password']);
        $sql = "UPDATE users SET password='".mysqli_real_escape_string($this->conn, $hashpassword)."' WHERE email_hash='" . $this->params['q'] . "'";
        $result = $this->Query($this->conn, $sql);
        echo 1;
      }
    }

    private function fp_user() {
      $checkEmail = $this->checkEmailExists();
      if($checkEmail) {
        echo 0;
      } else {
        $content = $this->resetPasswordEmailContent();
        $this->sendResetPasswordEmail($content);
        echo 1;
      }
    }

    private function verifyEmail(){
      try {
        $sql = "UPDATE users SET user_active=1 WHERE email_hash='" . $this->params['q'] . "'";
        $result = $this->Query($this->conn, $sql);
        $row_count = $this->AffectedRows($this->conn);
        if ($row_count > 0) {
            echo 1;
        } else {
            echo 0;
        }
      } catch (Exception $e) {

      }
    }

    private function login(){
      try {
        $npassword = hash('sha512', $GLOBALS['salt'] . $this->params['password']);
        $login_sql = "SELECT user_id, user_name, user_email FROM  users ";
        $login_sql .= "WHERE user_active=1 AND user_email =  '" . mysqli_real_escape_string($this->conn, $this->params['email']) . "' ";
        $login_sql .= "AND password='" . mysqli_real_escape_string($this->conn, $npassword) . "'";
        $login_sql .= ' LIMIT 1';
        $result = $this->Query($this->conn, $login_sql);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            $data = $this->FetchAssoc($result);
            //print_r($data);
            $_SESSION['user'] =  $data[0];
            echo 1;
        } else {
            echo 0;
        }
      } catch (Exception $e) {
        error_log($e);
      }

    }

    private function insUser() {
        $checkEmail = $this->checkEmailExists();
        if ($checkEmail) {
            $clientId = $this->createUser();
            $content = $this->prepareEmailContent();
            $this->sendVerificationEmail($content);
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    private function checkEmailExists() {
      try {
        $sql = "SELECT user_email FROM users WHERE user_email='" . $this->params['email'] . "'";
        $result = $this->Query($this->conn, $sql);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            return false;
        } else {
            return true;
        }
      } catch (Exception $e) {
        error_log($e);
      }
    }

    private function checkEmailHashExists() {
      try {
        $sql = "SELECT email_hash FROM users WHERE email_hash='" . $this->params['email'] . "'";
        $result = $this->Query($this->conn, $sql);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            return false;
        } else {
            return true;
        }
      } catch (Exception $e) {
        error_log($e);
      }
    }

    private function createUser() {
        $hashpassword = hash('sha512', $GLOBALS['salt'] . $this->params['password']);
        $hashemail = hash('sha512', $GLOBALS['salt'] . $this->params['email']);
        $sql = "INSERT INTO users (user_name, user_email, password, user_type, phone, email_hash, registered_at) VALUES ";
        $sql .= "('" . mysqli_real_escape_string($this->conn, $this->params['name']) . "',";
        $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['email']) . "',";
        $sql .= "'" . mysqli_real_escape_string($this->conn, $hashpassword) . "',";
        $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['usertype']) . "',";
        $sql .= "'" . mysqli_real_escape_string($this->conn, $this->params['phone']) . "',";
        $sql .= "'" . mysqli_real_escape_string($this->conn, $hashemail) . "',";
        $sql .= "'" . $this->date . "')";
        $this->Query($this->conn, $sql);
        return true;
    }

    private function prepareEmailContent() {
        //Send Email to reset the password//
        $passwordkey = hash('sha512', $GLOBALS['salt'] . mysqli_real_escape_string($this->conn, $this->params['email']));
        $pwrurl = $this->base . 'verify_email.php?q=' . $passwordkey;
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
                                Email Verification
                            </td>
                        </tr>
                        <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-wrap" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                        Dear ' . $this->params['name'] . ',
                                    </td>
                                </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            You have been registred to YellowSlate.com. Please click on the below button to complete your registration process.
                                        </td>
                                    </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px; text-align: center;" valign="top">
                                            <a href="' . $pwrurl . '" class="btn-primary" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #188ae2; margin: 0; border-color: #188ae2; border-style: solid; border-width: 10px 20px;">VERIFY ACCOUNT</a>
                                        </td>
                                    </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                              Copyright &copy; 2017. All Rights Reserved to <a href="http://yellowslate.com">YELLOWSLATE.COM</a>
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
      return $email_message;
    }

    private function resetPasswordEmailContent() {
        //Send Email to reset the password//
        $passwordkey = hash('sha512', $GLOBALS['salt'] . mysqli_real_escape_string($this->conn, $this->params['email']));
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
                                Reset password
                            </td>
                        </tr>
                        <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-wrap" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                        Dear User,
                                    </td>
                                </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            Please click on the below button to reset your password.
                                        </td>
                                    </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px; text-align: center;" valign="top">
                                            <a href="' . $pwrurl . '" class="btn-primary" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #188ae2; margin: 0; border-color: #188ae2; border-style: solid; border-width: 10px 20px;">RESET PASSWORD</a>
                                        </td>
                                    </tr>
                                    <tr style="font-family: tahoma; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: tahoma; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                              Copyright &copy; 2017. All Rights Reserved to <a href="http://yellowslate.com">YELLOWSLATE.COM</a>
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
      return $email_message;
    }

    private function sendVerificationEmail($content){
      try {
        $mail = new PHPMailer;
        $mail->isSendmail();
        $mail->isHTML(true);
        $mail->setFrom('admin@yellowslate.com', 'YellowSlate');
        //$mail->addAddress($this->params['email'], $this->params['name']);
        $mail->addAddress('rajesh@8views.com', $this->params['name']);
        $mail->Subject = 'Verify your account';
        $mail->msgHTML($content);
        if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
          } else {
            //echo "Message sent!"."<br />";
          }
      } catch (Exception $e) {

      }
    }

    private function sendResetPasswordEmail($content){
      try {
        $mail = new PHPMailer;
        $mail->isSendmail();
        $mail->isHTML(true);
        $mail->setFrom('admin@yellowslate.com', 'YellowSlate');
        //$mail->addAddress($this->params['email'], $this->params['name']);
        $mail->addAddress('rajesh@8views.com');
        $mail->Subject = 'Reset your password';
        $mail->msgHTML($content);
        if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
          } else {
            //echo "Message sent!"."<br />";
          }
      } catch (Exception $e) {

      }
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
