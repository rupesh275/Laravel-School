<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> <?php echo $this->lang->line('search_student'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-md-3 col-sm-12 uploadsticky">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2>Welcome To <?php echo $result->name; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-12 uploadsticky">
                <div class="box box-primary">
                    <div class="box-body text-center">
                        <?php
                        if ($result->image == "") {
                        ?>
                            <img src="<?php echo base_url() ?>uploads/school_content/logo/images.png" class="img-thumbnail" alt="Cinque Terre" width="304" height="236">
                        <?php
                        } else {
                        ?>
                            <img src="<?php echo base_url() ?>uploads/school_content/logo/<?php echo $result->image; ?>" class="img-thumbnail" alt="Cinque Terre" width="304" height="236">
                        <?php
                        }
                        ?>
                        <br />
                        <br />
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>