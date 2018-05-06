<?php
// This is a simple script that takes all of the scheduled appointments for a specified date ($todays_date), cross references with the students table for names, and outputs results into a "stations_list" table.
// This was used to generate lists to help direct parents and students to the correct table to pick up their Chromebook(s)
  require_once("../includes/initialize.php");

  $todays_date = "2017-08-10";
  $stmt = "SELECT * FROM pickups ";
  $stmt .= "WHERE date=:date";

  $params = array(":date"=>$todays_date);

  $todays_pickups = $database->query($stmt, $params);

  foreach($todays_pickups as $pickup){
    $pickup_students = $pickup['students'];
    $pickup_ids = explode(",", $pickup_students);
    $pick_stud_names = array();
    foreach($pickup_ids as $curr_id){
      $stud_stmt = "SELECT * FROM students ";
      $stud_stmt .= "WHERE id=:id";
      $stud_params = array(":id"=>$curr_id);
      $stud_result = $database->query($stud_stmt, $stud_params);
      $curr_name = $stud_result[0]['name'];
      array_push($pick_stud_names, $curr_name);
    }

    $stud_names_string = implode(", ",$pick_stud_names);

    $stud_insert = "INSERT INTO station_list ";
    $stud_insert .= "(parent_name, date, station, student_names) ";
    $stud_insert .= "VALUES (:parent_name, :date, :station, :name)";

    $ins_params = array(":parent_name"=>$pickup['parent_name'],":date"=>$todays_date, ":station"=>$pickup['station_id'], ":name"=>$stud_names_string);
    $ins_result = $database->cud_query($stud_insert, $ins_params);

  }

?>
