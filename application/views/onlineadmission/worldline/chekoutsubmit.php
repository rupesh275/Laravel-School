
<html>
<head>
    <title>Payment Checkout</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" / />
    <!-- <script src="https://www.paynimo.com/paynimocheckout/client/lib/jquery.min.js" type="text/javascript"></script> -->
    <link rel="stylesheet" href="<?php echo site_url('/gatway/assets/css/bootstrap.min.css'); ?>"></link>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo site_url('/gatway/assets/js/bootstrap.min.js'); ?>"></script>

<?php if($mer_array['enableEmandate'] == 1 && $mer_array['enableSIDetailsAtMerchantEnd'] == 1){}elseif($mer_array['enableEmandate'] == 1 && $mer_array['enableSIDetailsAtMerchantEnd'] != 1){ ?>
    <style type="text/css">
        .hid{
            display: none;
        }        
    </style>
<?php }else{ ?>
    <style type="text/css">
        .hid{
            display: none;
        }        
    </style>
<?php } ?>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Payment Details1</h2>
                <form method="post" id="form">
                    <input type="hidden" name="hashcode" value="<?php echo $hashcode; ?>">
                    <div>Are you sure to pay Rs.<?php echo $amount; ?></div>     
                    <input class="btn btn-danger" id="btnSubmit" type="submit" name="submit" value="Make Payment" />
                </form>
                <div id="worldline_embeded_popup"></div>
            </div>
        </div>
    </div>
<?php 
if($mer_array['enableEmandate'] == 1){
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#myamount').change(function() { 
            var amt = $(this).val();
            var maxamt = amt*2;
            $('#mymaxAmount').val(maxamt);
        });
    });
</script>
<?php } ?>
<script type="text/javascript" src="https://www.paynimo.com/Paynimocheckout/server/lib/checkout.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btnSubmit").click(function(e){
            e.preventDefault();
            var str = $("#form").serialize();
            $.ajax({
                    type: 'POST',
                    cache: false,
                    data: str,
                    url: '<?php echo site_url('site/worldline_payrequest_submit/'); ?>',                                            
                    success: function (response)
                    {
                        var obj = JSON.parse(response);
                        function handleResponse(res)
                        {
                            if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0300') {
                        // success block
                            } else if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0398') {
                        // initiated block
                            } else {
                        // error block
                            }   
                        };
                        var configJson = 
                        {
                            'tarCall': false,
                            'features': {
                                'showPGResponseMsg': true,
                                'enableNewWindowFlow': <?php if($mer_array['enableNewWindowFlow'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,   //for hybrid applications please disable this by passing false
                                'enableAbortResponse': true,
                                'enableExpressPay': <?php if($mer_array['enableExpressPay'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,  //if unique customer identifier is passed then save card functionality for end  end customer
                                'enableInstrumentDeRegistration': <?php if($mer_array['enableInstrumentDeRegistration'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,  //if unique customer identifier is passed then option to delete saved card by end customer
                                'enableMerTxnDetails': true,
                                'siDetailsAtMerchantEnd': <?php if($mer_array['enableSIDetailsAtMerchantEnd'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,
                                'enableSI': <?php if($mer_array['enableEmandate'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,
                                'hideSIDetails': <?php if($mer_array['hideSIConfirmation'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,
                                'enableDebitDay': <?php if($mer_array['enableDebitDay'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,
                                'expandSIDetails': <?php if($mer_array['expandSIDetails'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,
                                'enableTxnForNonSICards': <?php if($mer_array['enableTxnForNonSICards'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,
                                'showSIConfirmation': <?php if($mer_array['showSIConfirmation'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,
                                'showSIResponseMsg': <?php if($mer_array['showSIResponseMsg'] == 1){ echo 'true'; }else{ echo 'false'; } ?>,
                            },
                            
                            'consumerData': {
                                'deviceId': 'WEBSH2',
                                //possible values 'WEBSH1', 'WEBSH2' and 'WEBMD5'
                                //'debitDay':'10',
                                'token': obj['hash'],
                                'returnUrl': obj['data'][12],
                                /*'redirectOnClose': 'https://www.tekprocess.co.in/MerchantIntegrationClient/MerchantResponsePage.jsp',*/
                                'responseHandler': handleResponse,
                                'paymentMode': '<?php if(isset($mer_array['paymentMode'])){ echo $mer_array['paymentMode']; } ?>',
                                'checkoutElement': '<?php if($mer_array['embedPaymentGatewayOnPage'] == "1"){ echo "#worldline_embeded_popup"; } else { echo ""; } ?>',
                                'merchantLogoUrl': '<?php if(isset($mer_array['logoURL'])){ echo $mer_array['logoURL']; } ?>',  //provided merchant logo will be displayed
                                'merchantId': obj['data'][0],
                                'currency': obj['data'][15],
                                'consumerId': obj['data'][8],  //Your unique consumer identifier to register a eMandate/eNACH
                                'consumerMobileNo': obj['data'][9],
                                'consumerEmailId': obj['data'][10],
                                'txnId': obj['data'][1],   //Unique merchant transaction ID
                                'items': [{
                                    'itemId': obj['data'][14],
                                    'amount': obj['data'][2],
                                    'comAmt': '0'
                                }],
                                'cartDescription': '}{custname:'+obj['data'][13],
                                'merRefDetails': [
                                    {"name": "Txn. Ref. ID", "value": obj['data'][1]}
                                ],
                                'customStyle': {
                                    'PRIMARY_COLOR_CODE': '<?php if(isset($mer_array['primaryColor'])){ echo $mer_array['primaryColor']; } ?>',   //merchant primary color code
                                    'SECONDARY_COLOR_CODE': '<?php if(isset($mer_array['secondaryColor'])){ echo $mer_array['secondaryColor']; } ?>',   //provide merchant's suitable color code
                                    'BUTTON_COLOR_CODE_1': '<?php if(isset($mer_array['buttonColor1'])){ echo $mer_array['buttonColor1']; } ?>',   //merchant's button background color code
                                    'BUTTON_COLOR_CODE_2': '<?php if(isset($mer_array['buttonColor2'])){ echo $mer_array['buttonColor2']; } ?>'   //provide merchant's suitable color code for button text
                                },
                                'accountNo': obj['data'][11],    //Pass this if accountNo is captured at merchant side for eMandate/eNACH
                                'accountHolderName': obj['data'][16],  //Pass this if accountHolderName is captured at merchant side for ICICI eMandate & eNACH registration this is mandatory field, if not passed from merchant Customer need to enter in Checkout UI.
                                'ifscCode': obj['data'][17],        //Pass this if ifscCode is captured at merchant side.
                                'accountType': obj['data'][18],  //Required for eNACH registration this is mandatory field
                                'debitStartDate': obj['data'][3],
                                'debitEndDate': obj['data'][4],
                                'maxAmount': obj['data'][5],
                                'amountType': obj['data'][6],
                                'frequency': obj['data'][7]  //  Available options DAIL, WEEK, MNTH, QURT, MIAN, YEAR, BIMN and ADHO
                            }
                        };
                        
                        //console.log(configJson);       

                        $.pnCheckout(configJson);
                        if(configJson.features.enableNewWindowFlow)
                        {
                            pnCheckoutShared.openNewWindow();
                        }
                    }
            });

        });
    });
</script>

</body>
</html>