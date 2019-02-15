<?php 
require_once('../../config.php');
require($CFG->dirroot.'/local/bluescope/classes/utils.php');
global $DB;

$usertypeid = optional_param('usertypeid', 0, PARAM_INT);

if ($usertypeid) {
       $getusertypelist = \local_bluescope\utils::local_bluescope_selcatlist($usertypeid);
       echo  $getusertypelist;
}
