<?php
/*
 * Bluescope
 *
 * To generate SAP Completion Report 
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

$strtitle = get_string('sapcompletiontitle', 'local_bluescope');
$systemcontext = context_system::instance();
$url = new moodle_url('/local/bluescope/addusertype.php');

require_capability('local/bluescope:addusertype', $systemcontext);

// Set up PAGE object.
$PAGE->set_url($url);
$PAGE->set_context($systemcontext);
$PAGE->set_title($strtitle);
$PAGE->set_pagelayout('admin');
$PAGE->set_heading($strtitle);

$mform = new sapcompletion_form();

if ($data = $mform->get_data()) {
   //print_object($data);
   \local_bluescope\utils::local_bluescope_sapreport($data->startdate, $data->enddate, $data->excludesap);
   exit;
} else if ($mform->is_cancelled()) {
   redirect($CFG->wwwroot);
}

echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();
