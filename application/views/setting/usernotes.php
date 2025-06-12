<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-empire"></i> <?php echo $this->lang->line('front_cms'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <form id="form1" action="<?php echo site_url('admin/calendar/usernotes') ?>"  enctype="multipart/form-data" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Add Notes"; ?></h3>

                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">

                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg');unset($_SESSION['msg']); ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>      
                            <?php echo $this->customlib->getCSRF(); ?>  


                            <div class="formgroup10 form-group mb10">
                                <label for="exampleInputEmail1"><?php echo "Notes"; ?></label><small class="req"> *</small>
                            </div>  
                            <div class="form-group"> 
                                <textarea id="editor1" name="notes" placeholder="" type="text" class="form-control ss"><?php echo set_value('notes', !empty($result) ? $result['notes']:""); ?></textarea>   
                                <span class="text-danger"><?php echo form_error('notes'); ?></span>
                            </div>
                            <div class="dividerhr"></div>
                            <div class="col-md-2 ">
                                <input type="hidden" name="id" value="<?php echo !empty($result) ? $result['id']:""?>">
                                <button type="submit" class="btn cfees btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>

                            </div>

                        </div><!-- /.box-body -->
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->

            </form>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        var popup_target = 'media_images';
     
           CKEDITOR.replace('editor1',
                {
                    allowedContent: true
                });

        $('#mediaModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $(document).on('click', '.feature_image_btn', function (event) {
            $("#mediaModal").modal('toggle', $(this));
        });

        $(document).on('click', '.gallery_image', function (event) {
            $("#mediaModal").modal('toggle', $(this));
        });

        $('#mediaModal').on('show.bs.modal', function (event) {
            var a = $(event.relatedTarget) // Button that triggered the modal
            popup_target = a[0].id;
            var button = $(event.relatedTarget) // Button that triggered the modal
            console.log(popup_target);
            var $modalDiv = $(event.delegateTarget);
            $('.modal-media-body').html("");
            $.ajax({
                type: "POST",
                url: baseurl + "admin/front/media/getMedia",
                dataType: 'text',
                data: {},
                beforeSend: function () {

                    $modalDiv.addClass('modal_loading');
                },
                success: function (data) {
                    $('.modal-media-body').html(data);
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });
        });

        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $(document).on('click', '.img_div_modal', function (event) {
            $('.img_div_modal div.fadeoverlay').removeClass('active');
            $(this).closest('.img_div_modal').find('.fadeoverlay').addClass('active');

        });

        $(document).on('click', '.add_media', function (event) {
            var content_html = $('div#media_div').find('.fadeoverlay.active').find('img').data('img');
            var content_id = $('div#media_div').find('.fadeoverlay.active').find('img').data('fid');
            var is_image = $('div#media_div').find('.fadeoverlay.active').find('img').data('is_image');
            var content_type = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_type');
            var content_name = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_name');

            var vid_url = $('div#media_div').find('.fadeoverlay.active').find('img').data('vid_url');
            var content = "";

            if (popup_target === "media_images") {
                if (typeof content_html !== "undefined") {
                    if (is_image === 1) {
                        content = '<img src="' + content_html + '">';
                    } else if (content_type == "video") {

                        var youtubeID = YouTubeGetID(vid_url);


                        content = '<iframe id="video" width="420" height="315" src="//www.youtube.com/embed/' + youtubeID + '?rel=0" frameborder="0" allowfullscreen></iframe>';

                    } else {
                        content = '<a href="' + content_html + '">' + content_name + '</a>';

                    }
                    InsertHTML(content);
                    $('#mediaModal').modal('hide');
                }
            } else if (popup_target === "feature_image") {
                if (is_image === 1) {
                    addImage(content_html);
                } else {
                    //error show  
                }
                $('#mediaModal').modal('hide');
            } else if (popup_target === "gallery_images") {
                if (content_type === "image/gif" || content_type === "image/jpeg" || content_type === "image/png" || content_type === "video") {

                    insert_gallery(content_html, content_id, content_name, is_image);
                } else {
                    //error show  
                }


                $('#mediaModal').modal('hide');
            }

        });
        $(document).on("click", ".pagination li a", function (event) {
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            load_country_data(page);
        });
    });
    function YouTubeGetID(url) {
        var ID = '';
        url = url.replace(/(>|<)/gi, '').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
        if (url[2] !== undefined) {
            ID = url[2].split(/[^0-9a-z_\-]/i);
            ID = ID[0];
        } else {
            ID = url;
        }
        return ID;
    }

    function addImage(content_html) {
        $('.feature_image_url').attr('src', content_html);
        $('#image').val(content_html);
        $('#image_preview').css("display", "block");
    }
    $(document).on('click', '.delete_media', function () {
        $('.feature_image_url').attr('src', '');
        $('#image').val('');
        $('#image_preview').css("display", "none");
    });
    function InsertHTML(content_html) {
        // Get the editor instance that we want to interact with.
        var editor = CKEDITOR.instances.editor1;


        // Check the active editing mode.
        if (editor.mode == 'wysiwyg')
        {
            editor.insertHtml(content_html);
        } else
            alert('You must be in WYSIWYG mode!');
    }

    function insert_gallery(content_image, content_id, content_name, is_image) {
        var output = '';
        output += "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal gallery_img div_record_" + content_id + "'>";
        output += "<div class='fadeoverlay'>";
        output += "<img class='img-responsive' data-fid='" + content_id + "' data-content_name='" + content_name + "' data-is_image='" + is_image + "' data-img='" + content_image + "' src='" + content_image + "'>";
        output += "<input type='hidden' value='" + content_id + "' name='gallery_images[]'>";
        if (is_image == 1) {
            output += "<i class='fa fa-picture-o videoicon'></i>";
        } else {
            output += "<i class='fa fa-youtube-play videoicon'></i>";
        }
        output += "<div class='overlay3'>";
        output += "<a href='#' class='uploadclosebtn delete_gallery_img' data-record_id='" + content_id + "' data-toggle='modal' data-target='#confirm-delete'><i class=' fa fa-trash-o'></i></a>";
        output += "<p class='processing'>Processing...</p>";
        output += "</div>";
        output += "<p class=''>" + content_name + "</p>";
        output += "</div>";
        output += "</div>";
        $(output).appendTo(".gallery_content");
    }

    $(document).on('click', '.delete_gallery_img', function () {
        $(this).closest('.gallery_img').remove();

    });

</script>


