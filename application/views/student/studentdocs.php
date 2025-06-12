<style type="text/css">
    .checkbox-inline+.checkbox-inline,
    .radio-inline+.radio-inline {
        margin-left: 8px;
    }

    #loader {
        border: 12px solid #58c7f0;
        border-radius: 50%;
        border-top: 12px solid #4a4444;
        width: 70px;
        height: 70px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    .center {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        z-index: 999;
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$language        = $this->customlib->getLanguage();
$language_name   = $language["short_code"];
$is_prev_year = 0;
?>
<!-- <div id="loader" class="center" style="display:none"></div> -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">

                <h1>
                    <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?><small><?php echo "Student Document"; ?></small>
                </h1>
            </section>
        </div>
        <div>
            <?php
            if ($this->session->flashdata('msg')) {
                $msg = $this->session->flashdata('msg');
            ?>

                <?php echo $this->session->flashdata('msg');
                unset($_SESSION['msg']); ?>
            <?php } ?>
        </div>
        <div>
            <a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a>
            <aside class="studentsidebar">
                <div class="stutop" id="">
                    <!-- Create the tabs -->
                    <div class="studentsidetopfixed">
                        <p class="classtap"><?php echo $student["class"]; ?> <a href="#" data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i></a></p>
                        <ul class="nav nav-justified studenttaps">
                            <?php foreach ($class_section as $skey => $svalue) {
                            ?>
                                <li <?php
                                    if ($student["section_id"] == $svalue["section_id"]) {
                                        echo "class='active'";
                                    }
                                    ?>><a href="#section<?php echo $svalue["section_id"] ?>" data-toggle="tab"><?php print_r($svalue["section"]); ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php foreach ($class_section as $skey => $snvalue) {
                        ?>
                            <div class="tab-pane <?php
                                                    if ($student["section_id"] == $snvalue["section_id"]) {
                                                        echo "active";
                                                    }
                                                    ?>" id="section<?php echo $snvalue["section_id"]; ?>">
                                <?php
                                foreach ($studentlistbysection as $stkey => $stvalue) {
                                    if ($stvalue['section_id'] == $snvalue["section_id"]) {
                                ?>
                                        <div class="studentname">
                                            <a class="" href="<?php echo base_url() . "studentfee/addfee/" . $stvalue["id"] ?>">
                                                <div class="icon"><img src="<?php echo base_url() . $stvalue["image"]; ?>" alt="User Image"></div>
                                                <div class="student-tittle"><?php echo $stvalue["firstname"] . " " . $stvalue["lastname"]; ?></div>
                                            </a>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                        <div class="tab-pane" id="sectionB">
                            <h3 class="control-sidebar-heading">Recent Activity 2</h3>
                        </div>

                        <div class="tab-pane" id="sectionC">
                            <h3 class="control-sidebar-heading">Recent Activity 3</h3>
                        </div>
                        <div class="tab-pane" id="sectionD">
                            <h3 class="control-sidebar-heading">Recent Activity 3</h3>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <!-- /.control-sidebar -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo "Student Document"; ?></h3>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <a href="#" type="button" class="btn btn-primary btn-xs">
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
                                                        <a target="_blank" href="<?php echo base_url() . "student/view/" . $student['id']; ?>">
                                                            <td class="bozero"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                                        </a>

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
                                                        <th><?php echo "Email ID"; ?></th>
                                                        <td><?php echo $student['email']; ?></td>
                                                        <th><?php echo "Class Teacher"; ?></th>
                                                        <td> <?php if (!empty($class_teacher)) {
                                                                    echo $class_teacher['name'];
                                                                } ?>
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
                                                        <th><?php echo "Teacher Contact" ?></th>
                                                        <td><b class="text-danger"> <?php if (!empty($class_teacher)) {
                                                                                        echo $class_teacher['contact_no'];
                                                                                    }  ?> </b>
                                                        </td>

                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-bottom: 10px;"></div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <a href="javascript:void(0);" class="adddocs pull-right btn btn-primary btn-hover" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('processing') ?>">Add</a>
                            <form action="<?php echo base_url('student/student_docs_valid'); ?>" method="post" id="adddocs_valid">
                                <table class="table table-striped table-bordered table-hover  table-fixed-header">
                                    <thead class="header">
                                        <tr>
                                            <th align="left"><?php echo "SR.No"; ?></th>
                                            <th align="left">Document</th>
                                            <th align="left"><?php echo "Status"; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($checklistresult as $key => $checkrow) {
                                            $checkStatus = $checkrow;
                                        ?>
                                            <tr>
                                                <input type="hidden" name="checklist_id[]" value="<?php echo $checkrow['checklist_id']; ?>">
                                                <input type="hidden" name="student_id" value="<?php echo $student["id"]; ?>">
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $checkrow['item_name']; ?></td>
                                                <td>
                                                    <input type="radio" <?php if (!empty($checkStatus) && $checkStatus['checklist_id'] == $checkrow['id'] && $checkStatus['status'] == "Pending") {
                                                                            echo "checked";
                                                                        } ?> checked name="status<?php echo $checkrow['id']; ?>" value="Pending" id="status<?php echo $checkrow['id']; ?>1"> <label for="status<?php echo $checkrow['id']; ?>1">Pending</label>
                                                    <input type="radio" <?php if (!empty($checkStatus) && $checkStatus['checklist_id'] == $checkrow['id'] && $checkStatus['status'] == "Submitted") {
                                                                            echo "checked";
                                                                        } ?> name="status<?php echo $checkrow['id']; ?>" value="Submitted" id="status<?php echo $checkrow['id']; ?>2"> <label for="status<?php echo $checkrow['id']; ?>2">Submitted</label>
                                                    <input type="radio" <?php if (!empty($checkStatus) && $checkStatus['checklist_id'] == $checkrow['id'] && $checkStatus['status'] == "Not Required") {
                                                                            echo "checked";
                                                                        } ?> name="status<?php echo $checkrow['id']; ?>" value="Not Required" id="status<?php echo $checkrow['id']; ?>3"> <label for="status<?php echo $checkrow['id']; ?>3">Not Required</label>
                                                </td>
                                            </tr>

                                        <?php
                                            $i++;
                                        } ?>

                                    </tbody>
                                </table>
                                <div>
                                    <input type="hidden" name="condition_id" value="<?php echo $con_id;?>">
                                    <button type="submit" class="btn btn-primary pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php //echo $this->lang->line('processing')
                                                                                                                                                        ?>"> <?php echo "Submit";
                                                                                                                                                                ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>

    </section>
</div>

<div id="StudentDocsModel" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Student Documents</h4>
            </div>
            <div class="modal-body">
                <div class="classfeesForm">
                    <div class=" relative">
                        <div id="examfade"></div>
                        <div id="exammodal">
                            <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                        </div>
                        <div class="">
                            <form method="post" action="<?php echo site_url('student/addchecklistmst') ?>" id="adddocs_form">

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="table-responsive">

                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $this->lang->line(''); ?></th>
                                                        <th><?php echo $this->lang->line('document'); ?></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select name="checklist_id" id="checklist_id" class="form-control">
                                                                <?php
                                                                if (!empty($checklistNotExist)) {
                                                                    foreach ($checklistNotExist as $key => $checklistRow) {
                                                                        if (!in_array($checklistRow['id'], $pendSubIdList)) {

                                                                ?>
                                                                            <option value="<?php echo $checklistRow['id']; ?>"><?php echo $checklistRow['item_name']; ?></option>
                                                                <?php }
                                                                    }
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="radio" checked name="status" value="Pending" id="status"> <label for="status">Pending</label>
                                                            <input type="radio" name="status" value="Submitted" id="status"> <label for="status">Submitted</label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>


                                    </div>
                                </div>
                                <div class="row fees-footer">
                                    <div class="col-md-12">
                                        <input type="hidden" name="student_id" value="<?php echo $student["id"]; ?>">
                                        <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '.adddocs', function() {
        $('#StudentDocsModel').modal('show');
    });

    $(document).on('submit', 'form#adddocs_form', function(event) {
        event.preventDefault();

        var $this = $('.allot-fees');
        $.ajax({
            type: "POST",
            dataType: 'Json',
            url: $("#adddocs_form").attr('action'),
            data: $("#adddocs_form").serialize(), // serializes the form's elements.
            beforeSend: function() {
                $this.button('loading');

            },
            success: function(data) {
                if (data.status == "0") {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    $('#StudentDocsModel').modal('hide');
                    window.setTimeout(
                        function() {
                            location.reload(true)
                        },
                        2000
                    );
                }
            },
            complete: function() {
                $this.button('reset');

            }
        });

    })

    $(document).on('submit', 'form#adddocs_valid', function(event) {
        event.preventDefault();

        var $this = $('.allot-fees');
        $.ajax({
            type: "POST",
            dataType: 'Json',
            url: $("#adddocs_valid").attr('action'),
            data: $("#adddocs_valid").serialize(), // serializes the form's elements.
            beforeSend: function() {
                $this.button('loading');

            },
            success: function(data) {
                if (data.status == "0") {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                }else if(data.status == "2"){
                    successMsg(data.message);
                    Popup(data.response,true);
                    // window.setTimeout(
                    //     function() {
                    //         location.reload(true)
                    //     },
                    //     1000
                    // );
                } else {
                    Popup(data.response,false);
                    successMsg(data.message);
                }
            },
            
        });

    })
</script>

<script>
    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload) {
        var frame1 = $('<iframe />').attr("id", "printDiv");
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        // frameDoc.document.write('<html>');
        // frameDoc.document.write('<head>');
        // frameDoc.document.write('<title></title>');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        // frameDoc.document.write('</head>');
        // frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        // frameDoc.document.write('</body>');
        // frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            document.getElementById('printDiv').contentWindow.focus();
            document.getElementById('printDiv').contentWindow.print();
            $("#printDiv", top.document).remove();
            // frame1.remove();
            if (winload == false) {
                window.location.href='<?php echo site_url('student/create')?>';
            }else{
                location.reload(true);
            }
        }, 1000);

        return true;
    }
</script>