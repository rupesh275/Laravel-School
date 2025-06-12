<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body">
                        <?php
                        if (!empty($result)) {
                            $video = str_replace('{{video_id}}', $result['media_name'], $result['type_detail']);
                            echo $video;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
            </div>
        </div>
</div>