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
namespace local_bluescope;

defined('MOODLE_INTERNAL') || die();

/**
 * Utility functions
 * 
 * @package local_bluescope
 * @copyright : 2018 Pukunui
 * @author    : Priya Ramakrishnan, Pukunui {@link http://pukunui.com}
 * @license   : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils{
    /**
     * To list all the active categories
     *
     * @return array
     */
    public static function local_bluescope_categroylist() {
        global $DB;

        $output = '<div>
            <select name="allcat[]" id="allcat" multiple="multiple" size="15">';
        $sql = "SELECT id, name
            FROM {course_categories} cat
            WHERE visible = 1
            ORDER BY name";
        $catlist = $DB->get_records_sql($sql);
        foreach($catlist as $cl) {
            $output .= "<option value=$cl->id> $cl->name </option>";
        }
        $output .= "</select>";
        return $output;
    }
    /**
     * To list all the active categories for the selected usertype
     *
     * @return array
     */
    public static function local_bluescope_selcatlist($typeid=0) {
        global $DB;

        $output = '<div>
            <select name="selcat[]" id="selcat" multiple="multiple" size="15" style="width: 200px">';
        $sql = "SELECT cat.id, cat.name
            FROM {course_categories} cat
            JOIN {local_bs_usertype_cat} utc
            ON utc.categoryid = cat.id 
            WHERE utc.typeid = $typeid 
            ORDER BY name";
        $selcatlist = $DB->get_records_sql($sql);
        foreach($selcatlist as $sl) {
            $output .= "<option value=$sl->id> $sl->name </option>";
        }
        $output .= "</select>";//echo $output;
        return $output;
    }
    /**
     * To list all the active categories for the selected usertype
     *
     * @return array
     */
    public static function local_bs_getusertypes() {
        global $DB;
        $userlist = array();
        $userlist[0] = get_string('selusertype', 'local_bluescope');
        $sql = "SELECT id, types
            FROM {local_bs_usertypes}
        ORDER BY types";
        $userlist += $DB->get_records_sql_menu($sql);
        return $userlist;
    }
    /**
     * To delete the selected usertype
     *
     * @return array
     */
    public static function local_bluescope_deletelist($typeid) {
        global $DB, $CFG;
        
        // Delete the categories associated with user types
        $DB->delete_records('local_bs_usertype_cat', array('typeid' => $typeid));

        if (!$DB->count_records('local_bs_usertype_cat', array('typeid' => $typeid))) {
            $DB->delete_records('local_bs_usertypes', array('id' => $typeid));
        }
        redirect($CFG->wwwroot."/local/bluescope/addusertype.php");
    }
    /**
     * To generate SAP Completion Report 
     *
     * @return array
     */
    public static function local_bluescope_sapreport($startdate, $enddate, $excludesap) {
        global $CFG, $DB;
        $startdate = strtotime("midnight", $startdate);
        $enddate = strtotime("tomorrow", $enddate)-1;
        $filename = get_string('sapcompletion', 'local_bluescope').'_'.date("Ymd").'.csv';
        @header('Content-Disposition: attachment; filename='.$filename);
        @header('Content-Type: text/csv');
        $sapfieldid = $DB->get_field('user_info_field', 'id', array('shortname' => 'sapid'));      
        $sql = "SELECT CONCAT(cc.id, c.id) as id , c.id as courseid, 
                FROM_UNIXTIME(cc.timestarted, '%d%M%Y') as startdate,
                FROM_UNIXTIME(cc.timecompleted, '%d%M%Y') as enddate,
                uid.data as sapid
                FROM {course} c
                JOIN {course_completions} cc
                ON cc.course = c.id ";
        if ($excludesap) {
            $sql .= " JOIN {user_info_data} uid ";
        } else {
            $sql .= " LEFT JOIN {user_info_data} uid ";
        }
        $sql .= " ON uid.userid = cc.userid
                  AND uid.fieldid = $sapfieldid
                  WHERE (cc.timecompleted > $startdate AND cc.timecompleted < $enddate) ";
        $sapreportrec = $DB->get_records_sql($sql);
        $csvhead = get_string('sapid', 'local_bluescope').','.
                   get_string('courseid', 'local_bluescope').','.
                   get_string('startdate', 'local_bluescope').','.
                   get_string('completiondate', 'local_bluescope');
        $csvhead .= "\r\n";
        echo $csvhead;
        foreach ($sapreportrec as $sr) {
           $csvdata = $sr->sapid.','.$sr->courseid.','.$sr->startdate.','.$sr->enddate."\r\n";
           echo $csvdata;
        }
    }
} 
