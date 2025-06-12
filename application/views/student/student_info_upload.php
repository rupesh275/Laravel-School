<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('staff'); ?> <small><?php echo $this->lang->line('student1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('student') . " Info Upload" ; ?></h3>
                        <div class="pull-right box-tools">                            
                        </div>
                    </div>
                    <div class="box-body">      
                        <?php if ($this->session->flashdata('msg')) { ?> <div>  <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>
                        <br/>           
                        1. <?php echo $this->lang->line('import_staff_step1'); ?><br/>
                        2. First Column Should be Admission No.<br/>
                        3. Second Column Should be In Number Format Only . <br/>
                        4. 63139E+11 This Type of number is not allowed . <br/>
                        <hr/></div>
                    <hr/>
                    <form action="<?php echo site_url('student/student_info_upload') ?>"  id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">                                     
                                <div class="col-md-3">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo "Column"; ?></label><small class="req"> *</small>
                                        <select  id="column" name="column" class="form-control" >
                                            <option value="1">Apaar ID</option>
                                            <option value="2">Aadhar No</option>
                                            <option value="3">Saral ID</option>
                                            <option value="4">PEN No (UID No)</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('column'); ?></span>
                                    </div>
                                </div>
                                

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div></div>
                                <div class="col-md-6 pt20">
                                    <button type="submit" class="btn btn-info pull-right"> <?php echo " Upload "; ?></button>
                                </div>     

                            </div>
                        </div>


                    </form>

                    <div>



                    </div>
                </div>
                </section>
            </div>

