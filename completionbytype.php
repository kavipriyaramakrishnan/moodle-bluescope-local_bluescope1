<?php
/*
 * Bluescope
 *
 * Course Completion by Type Report
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

$strtitle = get_string('complreportbytype', 'local_bluescope');
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

$mform = new completionbytype_form();

if ($data = $mform->get_data()) {
    $params = array();
    $sql = "SELECT CONCAT(u.firstname, ' ', u.lastname) as name,
            x.sapid as sapid,
            x.type as type,
            c.fullname as fullname,
            c.idnumber as coursesapid,
            FROM_UNIXTIME(cc.timecompleted,'%d%m%Y %H:%i') as completiondate,
            (CASE WHEN cc.timecompleted IS NULL
             THEN 'Not yet Completed'
             ELSE 'Completed'
             END
            ) as status 
            FROM {user} u 
            JOIN {user_enrolments} ue ON ue.userid = u.id
            JOIN {enrol} e ON e.id = ue.enrolid   
            JOIN {course} c ON c.id = e.courseid
            JOIN {course_completions} cc ON cc.course = c.id
            AND cc.userid = u.id
            LEFT JOIN (SELECT u.id as userid, a.sapid, b.type 
                       FROM {user} u
                       LEFT JOIN (SELECT uid.data as sapid, uid.userid
                                  FROM {user_info_data} uid 
                                  JOIN {user_info_field} uif 
                                  ON uif.id = uid.fieldid 
                                  WHERE uif.shortname = 'sapid'
                                 ) as a
                       ON a.userid = u.id
                       LEFT JOIN (SELECT uid.data as type, uid.userid
                                  FROM {user_info_data} uid
                                  JOIN {user_info_field} uif
                                  ON uif.id = uid.fieldid
                                  WHERE uif.shortname = 'usertype'
                                 ) as b
                        ON b.userid = u.id
                      ) as x
            ON u.id = x.userid 
            WHERE 1=1  
            ";
    if (!empty($data->coursecategory)) {
        $sql .= " AND c.category = :categoryid ";
        $params['categoryid'] = $data->coursecategory;
    } else if (!empty($data->courselist)) {
        $sql .= " AND c.id = :courseid ";
        $params['courseid'] = $data->courselist;
    } else if (!empty($data->userlist)) { 
        $sql .= " AND u.id = :userid ";
        $params['userid'] = $data->userlist;
    }
    $completionrecords = $DB->get_records_sql($sql, $params); 
    $filename = 'bluescope_'.date("Ymd").'.csv';
    @header('Content-Disposition: attachment; filename='.$filename);
    @header('Content-Type: application/force-download');
    $csvhead = get_string('name', 'local_bluescope').','.
               get_string('sapid', 'local_bluescope').','.
               get_string('type', 'local_bluescope').','.
               get_string('course', 'local_bluescope').','.
               get_string('coursesapid', 'local_bluescope').','.
               get_string('lastaccessed', 'local_bluescope').','.
               get_string('completiondate', 'local_bluescope').','.
               get_string('results', 'local_bluescope');
    echo $csvhead;
    echo "\r\n";
    $typename = $DB->get_field('local_bs_usertypes', 'types', array('id' => $data->usertype));
    foreach ($completionrecords as $cr) {
       if (!strcmp(trim($cr->type), trim($typename))) {
           $csvcontent = "\"".$cr->name."\",".$cr->sapid.",".$cr->type.",\"".$cr->fullname."\",".$cr->coursesapid.","."lastaccessed".",".$cr->completiondate.",".$cr->status;
           echo $csvcontent;
           echo "\r\n";
       }
    }
    exit;
} else if ($mform->is_cancelled) {
    redirect($CFG->wwwroot);
}
echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();
