<?php
  // This is a simple script that takes all of the scheduled appointments for a specified date ($todays_date), cross references with the students table for names, and outputs results into a "stickers" table.
  // This was used to generate student labels to affix to each student's Chromebook.
  require_once("../includes/initialize.php");

  $todays_date = "2017-08-10";
  $stmt = "SELECT * FROM pickups ";
  $stmt .= "WHERE date=:date";

  $params = array(":date"=>$todays_date);

  $todays_pickups = $database->query($stmt, $params);

  foreach($todays_pickups as $pickup){
    $pickup_students = $pickup['students'];
    $pickup_ids = explode(",", $pickup_students);

    foreach($pickup_ids as $curr_id){
      $stud_stmt = "SELECT * FROM students ";
      $stud_stmt .= "WHERE id=:id";
      $stud_params = array(":id"=>$curr_id);
      $stud_result = $database->query($stud_stmt, $stud_params);
      $curr_name = $stud_result[0]['name'];

      $stud_insert = "INSERT INTO stickers ";
      $stud_insert .= "(date, station, student_name) ";
      $stud_insert .= "VALUES (:date, :station, :name)";

      $ins_params = array(":date"=>$todays_date, ":station"=>$pickup['station_id'], ":name"=>$curr_name);
      $ins_result = $database->cud_query($stud_insert, $ins_params);

    }

  }

?>
