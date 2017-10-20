<?php

include 'Premailer.php';

class emailAutomationController extends CommonController {

    private $params;
    private $conn;

    public function __construct($param, $conn) {
        $this->params = $param;
        $this->conn = $conn;
        switch ($this->params['action']) {
            case 'saveTemplate':
                if (!empty($this->params['src'])) {
                    if (empty($this->params['tID'])) {
                        $this->addEmailTemplate();
                    } else {
                        $this->editEmailTemplate();
                    }
                }
                break;
            case 'getTemplates':
                $this->getTemplates();
                break;
            case 'import':
                $this->import();
                break;
        }

        //print_r($this->params);
    }

    private function import() {
        $template_html = $this->params['tpl_html'];

        $query = 'INSERT INTO email_templates (client_id_fk, template_name, template_html) values (';
        $query .= "'" . $_SESSION['ge_permission']['client_id_fk'] . "', ";
        $query .= "'" . mysqli_real_escape_string($this->conn, $this->params['tpl_name']) . "', ";
        $query .= "'" . mysqli_real_escape_string($this->conn, $this->params['tpl_html']) . "') ";
        //echo $query;
        $result = $this->Query($this->conn, $query);
        echo 'https://growtheye.com/email_builder.php';
    }

    private function addEmailTemplate() {
        $template_html = $this->prepareHTMLTemplate();
        $query = 'INSERT INTO email_templates (client_id_fk, template_name, template_html, template_responsive) values (';
        $query .= "'" . $_SESSION['ge_permission']['client_id_fk'] . "', ";
        $query .= "'" . mysqli_real_escape_string($this->conn, $this->params['tName']) . "', ";
        $query .= "'" . mysqli_real_escape_string($this->conn, $template_html) . "', ";
        $query .= "'" . mysqli_real_escape_string($this->conn, $this->params['src']) . "') ";
        $result = $this->Query($this->conn, $query);
        echo 'https://growtheye.com/email_builder.php';
    }

    private function editEmailTemplate() {
        $template_html = $this->prepareHTMLTemplate();
        $query = " UPDATE email_templates SET ";
        $query .= " template_name='" . mysqli_real_escape_string($this->conn, $this->params['tName']) . "', ";
        $query .= " template_html='" . mysqli_real_escape_string($this->conn, $this->params['src']) . "' ";
        $query .= " WHERE template_id=" . $this->params['tID'];
        $result = $this->Query($this->conn, $query);
        echo 'https://growtheye.com/email_builder.php?template_id=' . $this->params['tID'];
    }

    private function prepareHTMLTemplate() {
        $startHTML = '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
<style type="text/css">
.Rockmail html
{
	width: 100%;
}

.Rockmail ::-moz-selection{background:#fefac7;color:#4a4a4a;}
.Rockmail ::selection{background:#fefac7;color:#4a4a4a;}

.Rockmail body {
   margin: 0;
   padding: 0;
}

.Rockmail .ReadMsgBody
{
	width: 100%;
	background-color: #f1f1f1;
}
.Rockmail .ExternalClass
{
	width: 100%;
	background-color: #f1f1f1;
}

.Rockmail a {
	color:#fab702; text-decoration: none; font-weight: normal; font-style: normal;
}

.Rockmail p,
.Rockmail div,
.Rockmail span {
	margin: 0 !important;
}
.Rockmail table {
	border-collapse: collapse;
}

@media only screen and (max-width: 599px)  {
	body { width: auto !important;}
	body table table{width:100% !important; }
	body td[class="wrapper_padding"] {width:100% !important; padding-right: 20px !important; padding-left: 20px !important;}

	body td[class="full"] {width: 100% !important; display: block !important; float: left; margin-bottom: 30px !important;}
	body td[class="full_no_margin"] {width: 100% !important; display: block !important; float: left; margin-bottom: 0px !important;}
	body td[class="rewrite_padding"] {width: 100% !important; display: block !important; float: left; padding: 50px 0px !important;}
	body td[class="custom_padding"] {width: 100% !important; padding: 0px 20px !important;}

	body td[class="center"] {text-align: center !important;}
	body td[class="right"] {text-align: right !important;}
	body td[class="spacer"] {display: none !important;}
	body img[class="img_scale"] {width: 100% !important; height: auto;}

}

@media only screen and (max-width: 479px)  {
	body { width: auto !important;}
	body table table{width:100% !important; }
	body td[class="wrapper_padding"] {width:100% !important; padding-right: 20px !important; padding-left: 20px !important;}

	body td[class="full"] {width: 100% !important; display: block !important; float: left; margin-bottom: 30px !important;}
	body td[class="full_no_margin"] {width: 100% !important; display: block !important; float: left; margin-bottom: 0px !important;}
	body td[class="rewrite_padding"] {width: 100% !important; display: block !important; float: left; padding: 50px 0px !important;}
	body td[class="custom_padding"] {width: 100% !important; padding: 0px 20px !important;}

	body td[class="center"] {text-align: center !important;}
	body td[class="right"] {text-align: right !important;}
	body td[class="spacer"] {display: none !important;}
	body img[class="img_scale"] {width: 100% !important; height: auto;}

}
</style>

<!--[if lt mso 14]>
    <style type="text/css">
    td span {
	font-family: Arial, sans-serif;
    }

    td a {
	font-family: Arial, sans-serif;
    }
	body {font-family: Arial, sans-serif !important;}
    </style>
<![endif]-->

<!--[if mso 15]>
    <style type="text/css">
    td span {
	font-family: Arial, sans-serif;
    }

     td a {
	font-family: Arial, sans-serif;
    }
	body {font-family: Arial, sans-serif !important;}
    </style>
<![endif]-->


</head>
<body style="margin:0; padding:0;"> ';
        $endHTML = '</body></html>';
        $src = str_replace('images/', 'https://growtheye.com/online-editor/demo/templates/Rockmail/images/', $this->params['src']);
        $HTML_Template = $startHTML . $this->params['src'] . $endHTML;
        $rawHTML = $this->inlinerApi($HTML_Template);
        return html_entity_decode($rawHTML);
    }

    private function inlinerApi($html) {

        //Prepare you post parameters
        $postData = array('returnraw ' => true, 'source ' => $html);
        ;
        //API URL
        $url = "https://inlinestyler.torchbox.com/styler/convert/";
        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
        ));
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //get response
        $output = curl_exec($ch);

        //Print error if any
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }

    private function prepareResponsiveHTMLTemplate($html) {
        $src = str_replace('images/', 'https://growtheye.com/online-editor/demo/templates/Rockmail/images/', $html);
        $pre = Premailer::html($src);
        $rhtml = $pre['html'];
        return $rhtml;
    }

    private function getTemplates() {
        $query = 'select template_id, template_name from email_templates where client_id_fk=' . $_SESSION['ge_permission']['client_id_fk'];
        $result = $this->Query($this->conn, $query);
        $row_count = $this->FetchNum($result);
        if ($row_count > 0) {
            $rows = $this->FetchAssoc($result);
            echo json_encode($rows);
        } else {
            echo 0;
        }
    }

    public function __destruct() {
        unset($this->params);
        $this->Close($this->conn);
    }

}
