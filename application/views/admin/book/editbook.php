<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('library'); ?> </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('edit_book'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <form id="form1" action="<?php echo site_url('admin/book/edit/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>   
                            <?php echo $this->customlib->getCSRF(); ?>                         
                            <input  type="hidden" name="id" value="<?php echo set_value('id', $editbook['id']); ?>" >
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_title'); ?> <small class="req"> *</small></label>
                                <input autofocus="" id="book_title" name="book_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_title', $editbook['book_title']); ?>" />
                                <span class="text-danger"><?php echo form_error('book_title'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_no'); ?></label>
                                <input id="book_no" name="book_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_no', $editbook['book_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('book_no'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Excession No'; ?></label>
                                <input id="excession_no" name="excession_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('excession_no' ,$editbook['excession_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('excession_no'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Category'; ?></label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select</option>
                                    <?php if (!empty($category)) { foreach ($category as  $categoryRow) {
                                        ?>
                                        <option value="<?php echo $categoryRow['id'] ;?>" <?php if ($editbook['category'] == $categoryRow['id']) {
                                                    echo "selected=selected";
                                                } ?>><?php echo $categoryRow['category'] ?></option>
                                        <? }
                                    } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('category'); ?></span>
                            </div>
                           
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Call No'; ?></label>
                                <input id="call_no" name="call_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('call_no', $editbook['call_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('call_no'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Barcode'; ?></label>
                                <input id="barcode" name="barcode" placeholder="" type="text" class="form-control"  value="<?php echo set_value('barcode', $editbook['barcode']); ?>" />
                                <span class="text-danger"><?php echo form_error('barcode'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?></label>
                                <select name="publish" id="publish" class="form-control">
                                    <option value="">Select</option>
                                    <?php if (!empty($publisherlist)) {
                                        foreach ($publisherlist as  $publisherRow) {
                                       ?>
                                       <option value="<?php echo $publisherRow['id'] ;?>" <?php if ($editbook['publish'] == $publisherRow['id']) {
                                                echo "selected=selected";
                                            } ?>><?php echo $publisherRow['publisher'] ?></option>
                                       <?
                                        }
                                    }?>
                                </select>
                                <span class="text-danger"><?php echo form_error('publish'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Place of Publication'; ?></label>
                                <input id="place_of_publication" name="place_of_publication" placeholder="" type="text" class="form-control"  value="<?php echo set_value('place_of_publication', $editbook['place_of_publication']); ?>" />
                                <span class="text-danger"><?php echo form_error('place_of_publication'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Date of Publication'; ?></label>
                                <input id="date_of_publication" name="date_of_publication" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_of_publication', $this->customlib->dateformat($editbook['date_of_publication'])); ?>" />
                                <span class="text-danger"><?php echo form_error('date_of_publication'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'No of Page'; ?></label>
                                <input id="no_of_page" name="no_of_page" placeholder="" type="text" class="form-control"  value="<?php echo set_value('no_of_page', $editbook['no_of_page']); ?>" />
                                <span class="text-danger"><?php echo form_error('no_of_page'); ?></span>
                            </div>
                          
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Classification No'; ?></label>
                                <input id="classification_no" name="classification_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('classification_no', $editbook['classification_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('classification_no'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Extent'; ?></label>
                                <input id="extent" name="extent" placeholder="" type="text" class="form-control"  value="<?php echo set_value('extent', $editbook['extent']); ?>" />
                                <span class="text-danger"><?php echo form_error('extent'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Physical Details'; ?></label>
                                <input id="physical_details" name="physical_details" placeholder="" type="text" class="form-control"  value="<?php echo set_value('physical_details', $editbook['physical_details']); ?>" />
                                <span class="text-danger"><?php echo form_error('physical_details'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Item Type'; ?></label>
                                <select name="item_type" id="item_type" class="form-control">
                                    <option value="">Select</option>
                                    <?php if (!empty($itemTypeList)) {
                                        foreach ($itemTypeList as  $itemRow) {
                                       ?>
                                       <option value="<?php echo $itemRow['id'] ;?>" <?php if ($editbook['item_type'] == $itemRow['id']) {
                                                echo "selected=selected";
                                            } ?>><?php echo $itemRow['item_type_name'] ?></option>
                                       <?
                                        }
                                    }?>
                                </select>
                                <span class="text-danger"><?php echo form_error('item_type'); ?></span>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('isbn_no'); ?></label>
                                <input id="isbn_no" name="isbn_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('isbn_no', $editbook['isbn_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('isbn_no'); ?></span>
                            </div>
                           


                            
                           
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?></label>
                                <select name="author" id="author" class="form-control">
                                    <option value="">Select</option>
                                    <?php if (!empty($authorlist)) {
                                        foreach ($authorlist as  $authorRow) {
                                       ?>
                                       <option value="<?php echo $authorRow['id']; ?>" <?php if ($editbook['author'] == $authorRow['id']) {
                                                echo "selected=selected";
                                            } ?>><?php echo $authorRow['author'] ?></option>
                                       <?
                                        }
                                    }?>
                                </select> <span class="text-danger"><?php echo form_error('author'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?></label>
                                <select name="subject" id="subject" class="form-control">
                                    <option value="">Select</option>
                                    <?php if (!empty($subjectlist)) {
                                        foreach ($subjectlist as  $subjectRow) {
                                       ?>
                                       <option value="<?php echo $subjectRow['id'] ;?>" 
                                       <?php if ($editbook['subject'] == $subjectRow['id']) {
                                                echo "selected=selected";
                                            } ?>><?php echo $subjectRow['subject_lib'] ?></option>
                                       <?
                                        }
                                    }?>
                                </select>
                                <span class="text-danger"><?php echo form_error('subject'); ?></span>
                            </div>
                           
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('rack_no'); ?></label>
                                <input id="rack_no" name="rack_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('rack_no', $editbook['rack_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('rack_no'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('qty'); ?></label>
                                <input id="qty" name="qty" placeholder="" type="text" class="form-control"  value="<?php echo set_value('qty', $editbook['qty']); ?>" />
                                <span class="text-danger"><?php echo form_error('qty'); ?></span>
                            </div>
                            
                           
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Purchase Cost'; ?></label>
                                <input id="purchase_cost" name="purchase_cost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('purchase_cost', $editbook['purchase_cost']); ?>" />
                                <span class="text-danger"><?php echo form_error('purchase_cost'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('bookprice'); ?></label>
                                <input id="price" name="price" placeholder="" type="text" class="form-control"  value="<?php echo set_value('price', $editbook['price']); ?>" />
                                <span class="text-danger"><?php echo form_error('price'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('postdate'); ?></label>
                                <input id="postdate" name="postdate"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('postdate', $this->customlib->dateformat($editbook['postdate'])); ?>" />
                                <span class="text-danger"><?php echo form_error('postdate'); ?></span>
                            </div>
                           
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description', $editbook['description']); ?></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">

                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>

            </div><!--/.col (right) -->


        </div>
        <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>