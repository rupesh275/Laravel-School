<?php
$return_string = "";
if (empty($main_subjects)) {
    
} else {
    ?>
    <option value="">Select Subject</option>
    <?php
    if (!empty($main_subjects)) {
        foreach ($main_subjects as $subject_key => $subject_value) {

            $sub_code=($subject_value['code'] != "") ? " (".$subject_value['code'].")":"";
            ?>
            <option value="<?php echo $subject_value['id'] ?>"><?php 
            echo $subject_value['name'].$sub_code; ?></option>
            <?php
        }
    }
}
?>
