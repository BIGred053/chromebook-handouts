<?php
// Handles logic of confirming and recording Chromebook handout, and includes error codes to warn of inconsistent data and duplicate entries.
    require_once("../includes/initialize.php");

    $num_students = $_POST['total_students'];
    for($i=0; $i<$num_students; $i++){
        $asset_id_name = "asset_tag".$i;
        $service_tag_name = "service_tag".$i;
        $student_id_name = "student_id".$i;

        $curr_asset_tag = $_POST[$asset_id_name];
        $curr_service_tag = $_POST[$service_tag_name];
        $curr_student_id = $_POST[$student_id_name];

        if($curr_student_id != ''){ // If non-null entry for student id, double-check to make sure it isn't already in the DB
            $stmt = "SELECT * FROM chromebooks WHERE student_id=:student_id";
            $params = array(":student_id"=>$curr_student_id);
            $uniq_stud_check = $database->query($stmt, $params);
            if(count($uniq_stud_check)>0){
              $_SESSION['message'] = "Error: Student already has assigned Chromebook ({$curr_student_id})";
              redirect_to("station_landing.php");
            }
        }

        if($curr_asset_tag != ''){ // If non-null entry for asset tag, double-check to make sure it isn't already in the DB
            $stmt = "SELECT * FROM chromebooks WHERE asset_tag=:asset_tag";
            $params = array(":asset_tag"=>$curr_asset_tag);
            $uniq_asset_check = $database->query($stmt, $params);

            if(count($uniq_asset_check)>0){
              $_SESSION['message'] = "Error: Asset tag already assigned ({$curr_asset_tag})";
              redirect_to("station_landing.php");
            }
        }

        if($curr_service_tag != ''){ // If non-null entry for service tag, double-check to make sure it isn't already in the DB
            $stmt = "SELECT * FROM chromebooks WHERE service_tag=:service_tag";
            $params = array(":service_tag"=>$curr_service_tag);
            $uniq_service_check = $database->query($stmt, $params);

            if(count($uniq_service_check)>0){
              $_SESSION['message'] = "Error: Service tag already assigned({$curr_service_tag})";
              redirect_to("station_landing.php");
            }
        }

        $stmt = "INSERT INTO chromebooks ";
        $stmt .= "(asset_tag, service_tag, student_id) ";
        $stmt .= "VALUES (:asset_tag, :service_tag, :student_id)";

        $params = array(":asset_tag"=>$curr_asset_tag, ":service_tag"=>$curr_service_tag, ":student_id"=>$curr_student_id);

        $result = $database->cud_query($stmt, $params);

    }
    $_SESSION['message'] = "";
    redirect_to("station_landing.php");
?>
