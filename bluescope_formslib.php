<?php
/*
 * Bluescope
 *
 * Forms required for the plugin are defined in this file
 *
 * @package   : local_bluescope
 * @copyright : 2018 Pukunui
 * @author    : Priya Ramakrishnan, Pukunui {@link http://pukunui.com}
 * @license   : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');

/*
 * Class addusertype_form extends moodleform
 * To add,display,delete user type and categories
 */
class addusertype_form extends moodleform {
    /*
     * Function definition to define Form elements
     */
    public function definition() {
        global $CFG, $DB, $USER, $OUTPUT;
        $mform =& $this->_form;
        $display  = $this->_customdata['display'];
        $usertype = $this->_customdata['usertype'];
        if (empty($usertype)) {
            $usertype = 0;
        }

        $usertypelist = \local_bluescope\utils::local_bs_getusertypes();
        $mform->addElement('select', 'usertype', get_string('usertype', 'local_bluescope'), $usertypelist);
        $mform->setType('usertype', PARAM_RAW);
        if ($display) {
            $mform->setDefault('usertype', $usertype);
        }
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'display', get_string('display', 'local_bluescope'));
        $buttonarray[] = &$mform->createElement('submit', 'addtype', get_string('add', 'local_bluescope'));
        $buttonarray[] = &$mform->createElement('submit', 'delete', get_string('delete', 'local_bluescope'));
        $mform->addGroup($buttonarray, 'buttonarr', '&nbsp;', array(''), false);

        $rarrow = $OUTPUT->rarrow();
        $larrow = $OUTPUT->larrow();
        $catlist = \local_bluescope\utils::local_bluescope_categroylist();
        $selcatlist = \local_bluescope\utils::local_bluescope_selcatlist($usertype);
        $coursecattable = "<table class='coursecate'>
                           <tr>
                              <td>".get_string('selectedcoursecat', 'local_bluescope'). "
                              <td> </td>
                              <td>".get_string('allcoursecate', 'local_bluescope'). "
                           </tr>
                           <tr>
                              <td> $selcatlist </td>
                              <td>
                                 <div id='addcontrols'>
                                    <input name='add' id='add' type='submit' value='$larrow ADD' title=print_string('add'); <br />
                                 </div>
                                 <div id='removecontrols'>
                                    <input name='remove' id='remove' type='submit' value='$rarrow REMOVE' title=print_string('remove');
                                 </div>
                              </td>
                              <td> $catlist </td>
                           </tr>
                           </table>";
        $mform->addElement('html', $coursecattable);
    }
}

/*
 * Class newusertype_form extends moodleform
 * To add new user type
 */
class newusertype_form extends moodleform {
    /*
     * Function definition to define Form elements
     */
    public function definition() {
        global $CFG, $DB, $USER, $OUTPUT;
        $mform =& $this->_form;
        $strrequired = get_string('required');
        $mform->addElement('text', 'usertype', get_string('usertype', 'local_bluescope'));
        $mform->setType('usertype', PARAM_RAW);
        $mform->addRule('usertype', $strrequired, 'required', null, 'client');
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'save', get_string('save', 'local_bluescope'));
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', get_string('cancel', 'local_bluescope'));
        $mform->addGroup($buttonarray, 'buttonarr', '&nbsp;', array(''), false);
    }
    /**
     * Function validation to validate form elements
     * @data holds the data Submitted form the Form
     * @files, files Submitted as part of the Form
     * @errors displays the Error message when encountered
     */
    public function validation($data, $files){
        $errors = parent::validation($data, $files);
        global $DB;
        if (!empty($data['save'])){
            if ($DB->count_records('local_bs_usertypes', array('types' => $data['usertype']))) {
                $errors['usertype'] = get_string('noduplicateutype', 'local_bluescope');
            }
        }
        return $errors;
    }
}

/*
 * Class sapcompletion_form extends moodleform
 * Form to display date filters to generate SAP Completion report.
 */

class sapcompletion_form extends moodleform {
    /*
     * Function definition to define Form elements
     */
    public function definition () {
        global $CFG, $DB, $USER, $OUTPUT;
        $mform =& $this->_form;
        $mform->addElement('date_selector', 'startdate', get_string('completionfrom', 'local_bluescope'));
        $mform->addElement('date_selector', 'enddate', get_string('completionto', 'local_bluescope'));
        $mform->addElement('advcheckbox', 'excludesap', get_string('excludesap', 'local_bluescope'));
        $mform->setDefault('excludesap', 1);
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'extract', get_string('extract', 'local_bluescope'));
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', get_string('cancel', 'local_bluescope'));
        $mform->addGroup($buttonarray, 'buttonarr', '&nbsp;', array(''), false);
    }
}

/*
 * Class completionbytype_form extends moodleform
 * Form to display date filters to generate Completion Report by User Type
 */
class completionbytype_form extends moodleform {
    /*
     * Function definition to define Form elements
     */
    public function definition() {
        global $CFG, $DB, $USER, $OUTPUT;
        $mform =& $this->_form;
        $catlist[0] = get_string('select');
        $courselist[0] = get_string('select');
        $userlist[0] = get_string('select');
        $utypelist = $DB->get_records_sql_menu("SELECT id, types FROM {local_bs_usertypes} ORDER BY types");
        $catlist += $DB->get_records_sql_menu("SELECT id, name FROM {course_categories} ORDER BY name");
        $courselist += $DB->get_records_sql_menu("SELECT id, fullname FROM {course} ORDER BY fullname");
        $userlist   += $DB->get_records_sql_menu("SELECT id, CONCAT(firstname, ' ', lastname) FROM {user} ORDER BY firstname, lastname");
        $mform->addElement('select', 'usertype', get_string('usertype', 'local_bluescope'), $utypelist);
        $mform->addElement('html', '<label>'.get_string('typecomment', 'local_bluescope').'</label>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<table><tr><td align="right">');
        //$mform->addElement('radio', 'radiobut', '', '', 0);
        //$mform->addElement('html', '<input type="radio" name="radiobut" value="radio1" onchange="disablecatsel(this)">');
        $mform->addElement('html', '<input type="radio" name="radiobut" value="radio1" id="id_radiobut_0">');
        $mform->addElement('html', '</td><td>');
        $mform->addElement('select', 'coursecategory', get_string('coursecategory', 'local_bluescope'), $catlist, array('disabled' => 'disabled'));
        $mform->addElement('html', '</td></tr><tr><td>');
        //$mform->addElement('radio', 'radiobut', '', '', 1);
        $mform->addElement('html', '<input type="radio" name="radiobut" value="radio1" id="id_radiobut_1">');
        $mform->addElement('html', '</td><td>');
        $mform->addElement('select', 'courselist', get_string('course', 'local_bluescope'), $courselist, array('disabled' => 'disabled'));
        $mform->addElement('html', '</td></tr><tr><td>');
        //$mform->addElement('radio', 'radiobut', '', '', 2);
        $mform->addElement('html', '<input type="radio" name="radiobut" value="radio1" id="id_radiobut_2">');
        $mform->addElement('html', '</td><td>');
        $mform->addElement('select', 'userlist', get_string('user', 'local_bluescope'), $userlist, array('disabled' => 'disabled'));
        $mform->addElement('html', '</td></tr></table>');
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', get_string('cancel', 'local_bluescope'));
        $buttonarray[] = &$mform->createElement('submit', 'view', get_string('view', 'local_bluescope'));
        $mform->addGroup($buttonarray, 'buttonarr', '&nbsp;', array(''), false);
    }
}
