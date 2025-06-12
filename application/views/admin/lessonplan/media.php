<?php
$is_image = "0";
$is_video = "0";
if (!empty($resultlist)) {
    foreach ($resultlist as $key => $result) {
        if ($result['media_type_id'] == 2) {
            $file     = base_url('backend/images/pdficon.png');
        } elseif ($result['media_type_id'] == 3) {
            $file     = base_url() . $result['media_name'];
        } elseif ($result['media_type_id'] == 1) {
            $file     = "https://i1.ytimg.com/vi/" . $result['media_name'] . "/sddefault.jpg";
        }
?>

        <div class="col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_">
            <div class="fadeoverlay">

                <div class="fadeheight">
                    <img class="" data-fid="<?php echo $result['id']; ?> " data-content_name=" <?php echo $result['media_name'] ?> " src="<?php echo $file; ?>">
                </div>
                <?php if ($result['media_type_id'] == 1) {
                ?>
                    <i class='fa fa-youtube-play videoicon'></i>
                <?
                } ?>
                <?php if ($result['media_type_id'] == 2 && $result['media_type_id'] == 3) {
                ?>
                    <i class='fa fa-picture-o videoicon'></i>
                <?
                } ?>
                <div class="overlay3">
                    <?php if ($result['media_type_id'] == 1) {
                    ?>
                        <a target="_blank" href="<?php echo base_url('admin/lessonplan/getvideopopup/' . $result['id']); ?>" id="popup<?php echo $result['id']; ?>" class="uploadcheckbtn popup-youtube"><i class='fa fa-navicon'></i></a>
                    <?
                    } else { ?>
                        <a target="_blank" href="<?php echo base_url() . $result['media_name']; ?>" class='uploadcheckbtn'><i class='fa fa-navicon'></i></a>
                    <?php } ?>
                    <a href="#" class="uploadclosebtn del_itemid" data-toggle="modal" data-target="#confirm-delete" data-record_id="<?php echo $result['id']; ?>"><i class="fa fa-trash-o"></i></a>
                </div>
                <b><?php echo $result['title']; ?></b>

            </div>
        </div>
<?php   }
}
?>
<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">