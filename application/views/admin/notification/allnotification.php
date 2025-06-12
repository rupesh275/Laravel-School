<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-bullhorn"></i> <?php echo $this->lang->line('communicate'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>   
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid1 box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-commenting-o"></i> <?php echo $this->lang->line('notification'); ?></h3>
                    </div>                 
                    <div class="box-body">
                        <div class="box-group" id="accordion"> 
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Unread</option>
                                            <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Read</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                         
                            <?php  if (empty($notificationlist)) { ?>
                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                <?php
                            } else { ?>
                            
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('noti_date'); ?></th>
                                        <th><?php echo $this->lang->line('noti_subject'); ?></th>
                                        <th style="display: none;"></th>
                                        <th><?php echo $this->lang->line('noti_status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($notificationlist as $result) {
                                    ?>
                                        <tr>
                                            
                                            <td class="mailbox-name">
                                                <?php echo date('d-m-Y',strtotime($result['notification_date'])); ?>
                                            </td>
                                            <td class="mailbox-name" id="sub<?php echo $result['id']; ?>">
                                                <?php
                                                    echo $result['status'] == 0 ? '<b>' : '';
                                                    echo $result['subject']; 
                                                    echo $result['status'] == 0 ? '</b>' : '';
                                                ?>
                                            </td>
                                            <td class="mailbox-name" style="display: none;" id="det<?php echo $result['id']; ?>">
                                                <?php echo $result['detail']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $result['status'] == 0 ? 'Unread' : 'Read'; ?>
                                            </td>
                                            <td class="mailbox-date pull-right">
                                                <a data-placement="left" href="javascript:void(0);" class="btn btn-default btn-xs view" id="<?php echo $result['id']; ?>" data-toggle="tooltip" title="" data-original-title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                   
                </div>
            </div>           
        </div>
</div>

</section>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $(".view").on("click",function(){
            var id = $(this).attr('id');
            var title = $("#sub"+id).text();
            var det = $("#det"+id).html();
            $(".header-title").text(title);
            $("#mbody").html(det);
            $.ajax({
                url: "<?php echo base_url('admin/notification/update_status');?>",
                type:"POST",
                data:{id:id},
                dataType:'json',
                success:function(data){
                    if(data.success){
                        $("#subjectModalHead").modal('show');
                    }
                }
            });
        });
        $(".btnclose").on("click",function(){
            // location.reload();
            $("#subjectModalHead").modal('hide');
        });
        $("#status").on("change",function(){
            var id = $(this).val();
            location.href = "<?php echo base_url('admin/notification/all/'); ?>"+id;
        });
    });
</script>