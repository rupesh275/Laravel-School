<div class="examheight100 relative">
    <div id="examfade"></div>
    <div id="exammodal">
        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
    </div>
    <div class="marksEntryupdate">
        <form method="post" action="<?php echo site_url('studentfee/classfeevalid') ?>" id="assign_form1112">
            
            <?php
            //if (isset($subjectList) && !empty($subjectList)) {
                // echo "<pre>";
                // print_r($student_AllSubjects);
            ?>
                <div class="row">

                    <div class="col-md-12">

                        <div class="table-responsive">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line(''); ?></th>
                                        <th><?php echo $this->lang->line('fees_group'); ?></th>
                                        <th><?php echo $this->lang->line('fees_code'); ?></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                        foreach ($feemasterList as $feegroup) {
                                            
                                            // echo "<pre>";
                                            // print_r ($feegroup);
                                            // echo "</pre>";

                                            $this->db->where('class_id', $class_id);
                                            // $this->db->where('section_id', $section_id);
                                            $this->db->where('fees_group_id', $feegroup->id);
                                            $feesarray         = $this->db->get('class_fees_mst')->row_array();
                                            
                                            
                                            // $feesarray         = $this->feegrouptype_model->getclassfess($class_id,$section_id,$feegroup->id)->row_array();
                                        ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="class_id" value="<?php echo $class_id;?>">
                                                    <!-- <input type="hidden" name="section_id" value="<?php //echo $section_id;?>"> -->
                                                    <input type="checkbox" <?php if(!empty($feesarray['fees_group_id']) == $feegroup->id){ echo "checked";}?> value="<?php echo $feegroup->id;?>" name="fees_group_id[]" id="fees_group_id">
                                                </td>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feegroup->group_name; ?></a>
                                                </td>


                                                <td class="mailbox-name">
                                                    <ul class="liststyle1">
                                                        <?php
                                                        foreach ($feegroup->feetypes as $feetype_key => $feetype_value) {
                                                        ?>
                                                            <li> <i class="fa fa-money"></i>
                                                                <?php echo $feetype_value->code . " " . $feetype_value->amount; ?> &nbsp;&nbsp;
                                                                

                                                            </li>

                                                        <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </td>

                                                
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                </tbody>
                            </table>

                        </div>

                       
                    </div>
                </div>
                <div class="row fees-footer">
        <div class="col-md-12">
        <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                            </button>
        </div>
    </div>
            <?php
           // } else {
            ?>

                <!-- <div class="alert alert-info">
                                    <?php // echo $this->lang->line('no_record_found'); ?>
                                </div> -->
            <?php
           // }
            ?>
        </form>
    </div>
</div>