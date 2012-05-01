<html>
<head>
</head>

<body>
<?php 
	session_start();
	$url                  = "https://tiwiii.com/sdfsdfsfdsdfsdf.php";
	
	//if(!isset($_POST)){
		if(isset($_POST['FullName']) && isset($_POST['Amount'])){
			//session_unset();
			//session_destroy();
			$_SESSION['Amount']   = filter_var($_POST['Amount'],FILTER_SANITIZE_STRING);;
			$_SESSION['FullName'] = filter_var($_POST['FullName'],FILTER_SANITIZE_STRING);;
			?>
			<script>
				window.location.href = 'https://tiwiii.com/sdfsdfsfdsdfsdf.php';
			</script>
			<?php
		}
	//}
	
	
	
	function leading_zeros($value, $places){
		$leading = NULL;
		if(is_numeric($value)){
			for($x = 1; $x <= $places; $x++){
				$ceiling = pow(10, $x);
				if($value < $ceiling){
					$zeros = $places - $x;
					for($y = 1; $y <= $zeros; $y++){
						$leading .= "0";
					}
				$x = $places + 1;
				}
			}
			$output = $leading . $value;
		}
		else{
			$output = $value;
		}
		return $output;
	}
	
	$amount           = filter_var($_SESSION['Amount'],FILTER_SANITIZE_STRING);
	if(!is_float($amount)){
		$amount = $amount.".00";
	}
	$Password         = "5Hz7Spk7";
	$MerID            = "9809646680";
	$AcqID            = "407387";
	$PurchaseCurrency = "462";
	$PurchaseAmt      = leading_zeros(str_replace('.', '', $amount),12);
	$OrderID          = "test1";
	$MerRespURL       = "https://tiwiii.com/sdfsdfsfdsdfsdf.php";
	
	$sig      = $Password.$MerID.$AcqID.$OrderID.$PurchaseAmt.$PurchaseCurrency;
	$sig_sha1 = sha1($sig,true);
	$sig_b64  = base64_encode($sig_sha1);
	
	
	echo "<strong>Signature</strong>: $sig";
	echo "</br>";
	echo "<strong>Signature SHA1 hash with base64_encode</strong>: $sig_sha1";
	echo "</br>";
	
	echo "<strong>Result:</strong>";
	print_r($_POST);
	echo "</br>";

?>
<!--
	MERCHANT NAME: Falim Group Pvt Ltd
    MERCHANT NUMBER: 9809646680
    TERMINAL ID: 91000041
    USERNAME: a.ali@falim.com.mv
    USER PASSOWRD: F1x3dbml 
    TRANSACTION PASSWORD: 5Hz7Spk7
-->

<form method="post"  action="https://testgateway.bankofmaldives.com.mv/SENTRY/PaymentGateway/Application/RedirectLink.aspx">
	    
    <input type="hidden" name="Version" value="1.0.0" />
    <input type="hidden" name="MerID" value="<?php echo $MerID; ?>" />
    <input type="hidden" name="AcqID" value="<?php echo $AcqID; ?>" />
    <input type="hidden" name="MerRespURL" value="<?php echo $MerRespURL; ?>" />
    <input type="hidden" name="PurchaseCurrency" value="<?php echo $PurchaseCurrency; ?>" />
    <input type="hidden" name="PurchaseCurrencyExponent" value="2" />
    <input type="hidden" name="OrderID" value="<?php echo $OrderID; ?>" />
    <input type="hidden" name="SignatureMethod" value="SHA1" />
    <input type="hidden" name="PurchaseAmt" value="<?php echo $PurchaseAmt; ?>" />
    <input type="hidden" name="FullName" value="<?php echo $_SESSION['FullName']; ?>" />
    <input type="hidden" name="Signature" value="<?php echo $sig_sha1; ?>" />
    <input type="submit" name="submit" value="Submit" />
    
</form>

</body>
</html>
