<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo "Medical Examination"; ?></h3>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>student/medical_exam" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                </div>
                            </div>

                        </div>
                    </div><!--./box-header-->
                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="sfborder">
                                    <div class="col-md-2">
                                        <img width="115" height="115" class="round5" src="<?php
                                                                                            if (!empty($student['image'])) {
                                                                                                echo base_url() . $student['image'];
                                                                                            } else {
                                                                                                echo base_url() . "uploads/student_images/no_image.png";
                                                                                            }
                                                                                            ?>" alt="No Image">
                                    </div>

                                    <div class="col-md-10">
                                        <div class="row">
                                            <table class="table table-striped mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td class="bozero"><?php echo $student['firstname'] . " " . $student['middlename'] . " " . $student['lastname']; ?></td>

                                                        <th class="bozero"><?php echo $this->lang->line('class_section'); ?></th>
                                                        <td class="bozero"><?php echo $student['class'] . " (" . $student['section'] . ")" ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                                        <td><?php echo $student['mobileno']; ?></td>
                                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                                        <td> <?php echo $student['roll_no']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('category'); ?></th>
                                                        <td>
                                                            <?php
                                                            foreach ($categorylist as $value) {
                                                                if ($student['category_id'] == $value['id']) {
                                                                    echo $value['category'];
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <?php //if ($sch_setting->rte) { 
                                                        ?>
                                                        <th><?php echo $this->lang->line('rte'); ?></th>
                                                        <td><b class="text-danger"> <?php echo $student['rte']; ?> </b>
                                                        </td>
                                                        <?php //} 
                                                        ?>

                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <!-- <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-bottom: 10px;"></div> -->
                            </div>
                        </div>
                        <form action="<?php echo base_url('student/medicalValid') ?>" method="post">
                            <div class="box-body">
                                <div class="tshadow mb25 bozero">
                                    <div class="around10">
                                        <?php $row = $this->student_model->getMedicResult($student['student_session_id']); ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputEmail1"><?php echo "Height"; ?></label>
                                                <input type="text" name="height" class="form-control" value="<?php echo !empty($row['height']) ? $row['height'] : ""; ?>">
                                                <span class="text-danger"><?php echo form_error('height'); ?></span>
                                            </div>
                                            <div class="col-md-6" style="padding-bottom: 8px">
                                                <label for="exampleInputEmail1"><?php echo "Weight"; ?></label>
                                                <input type="text" name="weight" class="form-control" value="<?php echo !empty($row['weight']) ? $row['weight'] : ""; ?>">
                                                <span class="text-danger"><?php echo form_error('weight'); ?></span>
                                            </div>
                                            <br>
                                            <?php
                                            foreach ($results as $key => $value) {
                                                $rowarr = $this->student_model->getMedicResult($student['student_session_id'], $value['id']);
                                            ?>
                                                <div class="col-md-4" style="padding-bottom: 8px">
                                                    <label for="exampleInputEmail1"><?php echo $value['name']; ?></label>
                                                    <input type="hidden" name="id[]" value="<?php echo !empty($rowarr['id']) ? $rowarr['id'] : ""; ?>">
                                                    <input type="hidden" name="exammst_id[]" value="<?php echo $value['id']; ?>">
                                                    <textarea id="content" name="content[]" placeholder="" class="form-control" rows="4"><?php echo set_value('content', !empty($rowarr['content']) ? $rowarr['content'] : ""); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('content'); ?></span>
                                                </div>
                                            <?

                                            } ?>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <input type="hidden" name="student_session_id" value="<?php echo $student['student_session_id']; ?>">
                                        <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
</div>


