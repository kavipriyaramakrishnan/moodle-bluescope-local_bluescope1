<?php
/*
 * Bluescope
 *
 * Library function 
 *
 * @package   : local_bluescope
 * @copyright : 2018 Pukunui
 * @author    : Priya Ramakrishnan, Pukunui {@link http://pukunui.com}
 * @license   : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*
function local_bluescope_categroylist() {
   global $DB;
  
   $output = '<div>
              <select name="allcat[]" id="allcat" multiple="multiple" size="15">';
   $sql = "SELECT id, name
           FROM {course_categories} cat
           ORDER BY name";
   $catlist = $DB->get_records_sql($sql);
   foreach($catlist as $cl) {
      $output .= "<option value=$cl->id> $cl->name </option>";
   }
   $output .= "</select>";
   return $output;
}

function local_bluescope_selcatlist($typeid=0) {
   global $DB;

   $output = '<div>
              <select name="selcat[]" id="selcat" multiple="multiple" size="15">';
   $sql = "SELECT cat.id, cat.name 
           FROM {course_categories} cat
           JOIN {local_bs_usertype_cat} utc
           ON utc.categoryid = cat.id";
   if ($typeid) {
       $sql .= " WHERE utc.typeid = $typeid";
   }
   $selcatlist = $DB->get_records_sql($sql);
   foreach($selcatlist as $sl) {
      $output .= "<option value=$sl->id> $sl->name </option>";
   }
   $output .= "</select>";
   return $output;
}

function local_bs_getusertypes() {
   global $DB;

   $sql = "SELECT id, types 
           FROM {local_bs_usertypes}
           ORDER BY types";
   $userlist = $DB->get_records_sql_menu($sql);
   return $userlist;
}*/
/**
 * To add the selected Categories to the selected Usertype
 *
 * @return array
 */
function add_cat_to_usertype($dat, $usertype) {
    global $DB;

    $count =  $DB->get_records('local_bs_usertype_cat', array('typeid' => $usertype, 'categoryid' => $dat));
    if (empty($count)) {
        $record = new stdClass();
        $record->typeid = $usertype;
        $record->categoryid = $dat;
        $DB->insert_record('local_bs_usertype_cat', $record);
    }
}
/**
 * To remove the selected Categories from the selected Usertype
 *
 * @return array
 */
function remove_cat_from_usertype($dat, $usertype) {
    global $DB;

    $DB->delete_records('local_bs_usertype_cat', array('typeid' => $usertype, 'categoryid' => $dat));
}




