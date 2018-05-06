<?php
  // This script was built to identify students who were not accurately tracked during the Chromebook handout process. This included some data due to initial bugs that were
  // later patched, as well as students who came in without scheduling a handout appointment who may not have been officially logged into the system. 
  require_once("../includes/initialize.php");

  // Get all students in students table
  $stmt = "SELECT * FROM students";
  $params = array();
  $all_students = $database->query($stmt, $params);
  $student_ids = array();

  if(!empty($all_students)){
    foreach ($all_students as $student) {
      $this_id = $student['district_id'];
      array_push($student_ids, $this_id);
    }
  }

  // Get all Chromebooks in chromebooks table (there may be a few that aren't in students table)
  $stmt = "SELECT * FROM chromebooks";
  $params = array();
  $all_chromebooks = $database->query($stmt, $params);
  $chromebook_ids = array();
  $ids_in_cb_not_students = array();

  foreach($all_chromebooks as $chromebook){
    $this_student = $chromebook['student_id'];
    array_push($chromebook_ids, $this_student);
    $stmt = "SELECT * FROM students ";
    $stmt .= "WHERE district_id=:district_id";
    $params = array(":district_id"=>$this_student);
    $student_check = $database->query($stmt, $params);
    if(empty($student_check)){
      array_push($ids_in_cb_not_students, $this_student);
      array_push($student_ids, $this_student);
    }
  }

  if(count($ids_in_cb_not_students)>0){
      echo "Some extra students in CB table not in students table.";
      print_r($ids_in_cb_not_students);
  }

  $unregistered_students = array();
  $stmt = "SELECT * FROM sis";
  $params = array();
  $dasl_students = $database->query($stmt, $params);

  foreach($dasl_students as $dasl_student){
    $dasl_id = $dasl_student['district_id'];
    $found = 0;
    foreach($all_students as $curr_student){
      if($dasl_id == $curr_student){
        $found = 1;
      }
    }

  }

?>
