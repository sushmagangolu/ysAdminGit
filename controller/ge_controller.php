<?php

include 'service_controller.php';

class geController extends serviceController {

    public $params;

    public function __construct($conn) {
        $this->params = $_REQUEST;
        $obj = new serviceController($this->params, $conn);
        switch ($this->params['action']) {
            case 'getForm':
                $obj->getForm();
                break;
            case 'saveForm':
                $obj->saveForm();
                break;
            case 'insert_lead':
                $obj->insert_lead();
                break;
            case 'edit_lead':
                $obj->edit_lead();
                break;
            case 'get_leads':
                $obj->get_leads();
                break;
            case 'get_leadInfo':
                $obj->get_leadInfo();
                break;
            case 'getFormAndDetails':
                $obj->getFormAndDetails();
                break;
            case 'add_comment':
                $obj->addComment();
                break;
            case 'getRemarks':
                $obj->getRemarks();
                break;
            case 'add_reminder':
                $obj->addReminder();
                break;
            case 'delete_leads':
                $obj->deleteLeads();
                break;
            case 'get_trash':
                $obj->get_trash();
                break;
             case 'restore_leads':
                $obj->restoreLeads();
                break;
            case 'getFormBuild':
                  $obj->getFormBuild();
                   break;
                   case 'saveFormBuild':
                      $obj->saveFormBuild();
                      break;
                      case 'get_leads_all':
                          $obj->get_leads_all();
                          break;
                          case 'get_leads_V3':
                              $obj->get_leads_V3();
                              break;
                              case 'assign_leads':
                                  $obj->assignLeads();
                                  break;
                                  case 'get_leads_V3_creports':
                                      $obj->get_leads_V3_creports();
                                      break;

        }
    }

    public function __destruct() {
        unset($this->params);
    }

}
