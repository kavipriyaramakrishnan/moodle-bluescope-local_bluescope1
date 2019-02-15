<?php
/*
 * Bluescope
 *
 * To add new user type to the and to allocate categories
 *
 * @package   : local_bluescope
 * @copyright : 2018 Pukunui
 * @author    : Priya Ramakrishnan, Pukunui {@link http://pukunui.com}
 * @license   : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once('../../config.php');
require($CFG->dirroot.'/local/bluescope/classes/utils.php');
require($CFG->dirroot.'/local/bluescope/locallib.php');
require($CFG->dirroot.'/local/bluescope/bluescope_formslib.php');

$action  = optional_param('action', '', PARAM_RAW);
$add     = optional_param('add', 0, PARAM_RAW);
$allcat  = optional_param('allcat', array(), PARAM_ALPHANUMEXT);
$display = optional_param('display', 0, PARAM_RAW);
$remove = optional_param('remove', 0, PARAM_RAW);
$selcat = optional_param('selcat', array(), PARAM_ALPHANUMEXT);
$usertype = optional_param('usertype', '', PARAM_INT);

$strtitle = get_string('addusertypetitle', 'local_bluescope');
$systemcontext = context_system::instance();
$url = new moodle_url('/local/bluescope/addusertype.php');

require_capability('local/bluescope:addusertype', $systemcontext);

// Set up PAGE object.
$PAGE->set_url($url);
$PAGE->set_context($systemcontext);
$PAGE->set_title($strtitle);
$PAGE->set_pagelayout('admin');
$PAGE->set_heading($strtitle);

// Including Javascript.
$PAGE->requires->js(new moodle_url('/local/bluescope/main.js'));
//print_object($_POST);
if (!empty($action) && !strcmp($action, 'confirmd')) {
    \local_bluescope\utils::local_bluescope_deletelist($usertype);
}
if (!empty($add) && !empty($allcat)) {
    foreach ($allcat as $key => $dat) {
       add_cat_to_usertype($dat, $usertype);
    }
}

if (!empty($remove) && !empty($selcat)) {
    foreach ($selcat as $key => $dat) {
       remove_cat_from_usertype($dat, $usertype);
    }
}

$mform = new addusertype_form('', array('display' => $display, 'usertype' => $usertype));

if ($data = $mform->get_data()) {
    if (!empty($data->addtype)) {
        redirect($CFG->wwwroot."/local/bluescope/newusertype.php");
    } else if (!empty($data->display)) {
        \local_bluescope\utils::local_bluescope_selcatlist($data->usertype);       
    } else if (!empty($data->delete)) {
        if (!empty($data->usertype)) {
            $linkyes = "$CFG->wwwroot/local/bluescope/addusertype.php?usertype=$data->usertype&action=confirmd";
            $linkno  = "$CFG->wwwroot/local/bluescope/addusertype.php?usertype=$data->usertype&display=display";
            echo $OUTPUT->header();
            echo $OUTPUT->confirm(get_string('confirmdelutype', 'local_bluescope'), $linkyes, $linkno);
            echo $OUTPUT->footer();
            exit();
        }
    }
}

// Output renderes
echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();
