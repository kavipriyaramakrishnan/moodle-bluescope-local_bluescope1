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
require($CFG->dirroot.'/local/bluescope/locallib.php');
require($CFG->dirroot.'/local/bluescope/bluescope_formslib.php');

$action = optional_param('action', '', PARAM_RAW);
$strtitle = get_string('newusertypetitle', 'local_bluescope');
$systemcontext = context_system::instance();
$url = new moodle_url('/local/bluescope/newusertype.php');

require_capability('local/bluescope:addusertype', $systemcontext);

// Set up PAGE object.
$PAGE->set_url($url);
$PAGE->set_context($systemcontext);
$PAGE->set_title($strtitle);
$PAGE->set_pagelayout('admin');
$PAGE->set_heading($strtitle);

$mform = new newusertype_form();

if ($data = $mform->get_data()) {
    if (!empty($data->save)) {
        $record = new Stdclass();
        $record->types = $data->usertype;
        $DB->insert_record('local_bs_usertypes', $record);
        redirect($CFG->wwwroot."/local/bluescope/addusertype.php");
    }
} else if (!empty($mform->is_cancelled())) {
    redirect($CFG->wwwroot."/local/bluescope/addusertype.php");
    exit;
}

// Output renderes
echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();

