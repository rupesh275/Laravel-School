<?php
	/**
	 * This Is the Kit File To Be Included For Transaction Request
	 */
	include 'AWLMEAPI.php';
	
	//create an Object of the above included class
	$obj = new AWLMEAPI();

	//create an object of Request Message
	$reqMsgDTO = new ReqMsgDTO();

	/* Populate the above DTO Object On the Basis Of The Received Values */
	// PG MID
	$reqMsgDTO->setMid($_REQUEST['mid']);
	// Merchant Unique order id
	$reqMsgDTO->setOrderId($_REQUEST['OrderId']);
	//Transaction amount in paisa format
	$reqMsgDTO->setTrnAmt($_REQUEST['amount']);
	//Transaction remarks
	$reqMsgDTO->setTrnRemarks("Any Remarks");
	// Merchant transaction type (S/P/R)
	$reqMsgDTO->setMeTransReqType($_REQUEST['meTransReqType']);
	// Merchant encryption key
	$reqMsgDTO->setEnckey($_REQUEST['enckey']);
	// Merchant transaction currency
	$reqMsgDTO->setTrnCurrency($_REQUEST['currencyName']);
	// Recurring period, if merchant transaction type is R
	$reqMsgDTO->setRecurrPeriod($_REQUEST['recurPeriod']);
	// Recurring day, if merchant transaction type is R
	$reqMsgDTO->setRecurrDay($_REQUEST['recurDay']);
	// No of recurring, if merchant transaction type is R
	$reqMsgDTO->setNoOfRecurring($_REQUEST['numberRecurring']);
	// Merchant response URl
	$reqMsgDTO->setResponseUrl($_REQUEST['responseUrl']);
	// Optional additional fields for merchant
	$reqMsgDTO->setAddField1($_REQUEST['addField1']);
	$reqMsgDTO->setAddField2($_REQUEST['addField2']);
	$reqMsgDTO->setAddField3($_REQUEST['addField3']);
	$reqMsgDTO->setAddField4($_REQUEST['addField4']);
	$reqMsgDTO->setAddField5($_REQUEST['addField5']);
	$reqMsgDTO->setAddField6($_REQUEST['addField6']);
	$reqMsgDTO->setAddField7($_REQUEST['addField7']);
	$reqMsgDTO->setAddField8($_REQUEST['addField8']);
	
	/* 
	 * After Making Request Message Send It To Generate Request 
	 * The variable `$urlParameter` contains encrypted request message
	 */
	 //Generate transaction request message
	$merchantRequest = "";
	
	$reqMsgDTO = $obj->generateTrnReqMsg($reqMsgDTO);
	
	if ($reqMsgDTO->getStatusDesc() == "Success"){
		$merchantRequest = $reqMsgDTO->getReqMsg();
	}
?>


<form action="https://cgt.in.worldline-solutions.com/ipg/doMEPayRequest" method="post" name="txnSubmitFrm">
	<h4 align="center">Redirecting To Payment Please Wait..</h4>
	<h4 align="center">Please Do Not Press Back Button OR Refresh Page</h4>
	<input type="hidden" size="200" name="merchantRequest" id="merchantRequest" value="<?php echo $merchantRequest; ?>"  />
	<input type="hidden" name="MID" id="MID" value="<?php echo $reqMsgDTO->getMid(); ?>"/>
</form>
<script  type="text/javascript">
	//submit the form to the worldline
	document.txnSubmitFrm.submit();
</script>