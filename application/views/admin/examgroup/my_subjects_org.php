<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
       
        <div class="box box-info" id="box_display">
            <?php if (!empty($resultlist)) {
                
                // echo "<pre>";
                // print_r ($resultlist);die;
                // echo "</pre>";
                
            ?>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Name</th>
                        <th>Session</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php foreach ($resultlist as  $value) {
                            
                            // echo "<pre>";
                            // print_r ($value);
                            // echo "</pre>";
                            
                        ?>
                            <tr>
                                <td><?php echo $value['class']; ?></td>
                                <td><?php echo $value['section']; ?></td>
                                <td><?php echo $value['name']; ?></td>
                                <td><?php echo $current_session_name; ?></td>
                                <td><a class="btn btn-default btn-xs first_modal" href="<?php echo base_url('admin/examgroup/exams_list/'. $value['subject_id'].'/'.$value['class_id'].'/'.$value['section_id'].'/'.$value['session_id']); ?>" role="button" data-toggle="modal" title="" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-original-title="Exam Marks"><i class="fa fa-newspaper-o" aria-hidden="true"></i></a></td>
                            </tr>
                        <?php

                        } ?>
                    </tbody>
                </table>
            <?php
            } ?>
        </div>
    </section>
</div>

<script type="text/javascript">
    // $(document).ready(function() {

    // });

    $(document).on('change', '#class_id', function(e) {

        $('#section_id').html("");
        var class_id = $(this).val();

        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var url = "";
        $.ajax({
            type: "GET",
            url: baseurl + "sections/getByClass",
            data: {
                'class_id': class_id
            },
            dataType: "json",
            success: function(data) {
                $.each(data, function(i, obj) {
                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                });
                $('#section_id').append(div_data);
            }
        });
    });
</script>