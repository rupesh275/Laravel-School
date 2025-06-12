<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form id="form" method="post" class="class_search_formss">
                            <?php if ($this->session->flashdata('msg')) { ?> <div class="alert alert-success"> <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- <div class="row"> -->
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                            <select autofocus="" id="class_id" name="class_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                $count = 0;
                                                foreach ($classlist as $class) {
                                                ?>
                                                    <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?>><?php echo $class['class'] ?></option>
                                                <?php
                                                    $count++;
                                                }
                                                ?>
                                            </select>
                                            <div class="text-danger" id="error-class_id"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('division'); ?></label><small class="req"> *</small>
                                            <select id="section_id" name="section_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger" ><?php echo form_error('section_id'); ?></span>
                                            <div class="text-danger" id="error-section_id"></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('month'); ?></label><small class="req"> *</small>
                                            <select id="month" name="month" class="form-control">
                                                <?php
                                                date_default_timezone_set('America/New_York');
                                                for ($i = 1; $i <= 12; $i++) {
                                                    $month = date('F', mktime(0, 0, 0, $i, 10));
                                                ?>
                                                    <option value="<?php echo $month; ?>" <?php if (set_value('month') == $month) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?>><?php echo $month; ?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger" ><?php echo form_error('month'); ?></span>
                                            <div class="text-danger" id="error-month"></div>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        <!-- <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button> -->
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                <!--./col-md-6-->
            </div>
            <!--./row-->
        </div>


        
</div>
<!--./box box-primary -->
<?php
//  }
?>
</div>
</div>

</section>
</div>


<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }
    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
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
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        table = $('.student-listss').DataTable({
            // "scrollX": true,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    className: "btn-copy",
                    title: $('.student-list').data("exportTitle"),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    className: "btn-excel",
                    title: $('.student-list').data("exportTitle"),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    className: "btn-csv",
                    title: $('.student-list').data("exportTitle"),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    className: "btn-pdf",
                    title: $('.student-list').data("exportTitle"),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    },

                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    className: "btn-print",
                    title: $('.student-list').data("exportTitle"),
                    customize: function(win) {

                        $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                        // $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                        $(win.document.body).find('h1').css('text-align', 'center');
                        $(win.document.body).find('td').css('text-align', 'left');
                    },
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]

                    }

                }
            ],
            "order": [
                [2, 'asc']
            ],
        });

        $("form.class_search_form button[type=submit]").click(function() {
            $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });

    });

 
    

    


    $("#form").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: baseurl + "student/attend_sheet_print",
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function(data) {
                // $this.button('loading');
            },
            success: function(data) {
                if (data.error) {
                    $.each(data, function(key, value) {
                        if (value) {
                            $('#error-' + key).html(value);
                            $('#input-' + key).addClass("border-danger");
                        } else {
                            $('#error-' + key).html(" ");
                            $('#input-' + key).removeClass("border-danger");
                        }
                    });
                }
                if (data.success) {
                    $('#form .form-control').removeClass("error");
                    $('#form .error').html(" ");
                    Popup(data.response);
                }
            }
            
        });
    });
</script>
<script>
    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false) {
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
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            document.getElementById('printDiv').contentWindow.focus();
            document.getElementById('printDiv').contentWindow.print();
            $("#printDiv", top.document).remove();
            // frame1.remove();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);

        return true;
    }
</script>