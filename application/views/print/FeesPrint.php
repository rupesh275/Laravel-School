<style>
    .w70 {
        width: 70%;
        
    }

    @media print {
        .w70 {
            width: 100%;
          
        }
        
        #btn,#btn2 {
            display: none !important;
        }

    }
</style>
<div class="" style="display:flex;justify-content: center;">
    <div class="w70">
        <div class="" style="justify-content: center;
            display: flex;">
            <?php if($source=='parent') { ?>
                <a href="<?php echo base_url('user/user/getfees')  ?>" class="btn btn-primary" id="btn2">Back</a>
            <?php }elseif($source=='counter-main' && $mode != 'cheque') { ?> 
                <a href="<?php echo base_url('studentfee/addfee/'.$st_session_id)  ?>" class="btn btn-primary" id="btn2">Back</a>
            <?php }elseif($source=='counter-main' && $mode == 'cheque') { ?> 
                <a href="<?php echo base_url('admin/feemaster/cheque_in_word')  ?>" class="btn btn-primary" id="btn2">Back</a>
            <?php } ?>
            <a href="javascript:void();" class="btn btn-primary ml-3" id="btn">Print</a>
        </div>
       
        <br>
        <div class="invoice">
            <?php echo $invoice ?>
        </div>
    </div>
</div>
<script>
    $('#btn').click(function() {
        Popup($('.invoice')[0].outerHTML);
        function Popup(data) {
            window.print();
            return true;
        }
    });
</script>