<?php
	//declare our assets 
	$name = stripcslashes($_POST['name']);
	$emailAddr = stripcslashes($_POST['email']);
	$comment = stripcslashes($_POST['message']);
	$subject = stripcslashes($_POST['subject']);	
	$contactMessage =  
		"Message:
		$comment 

		Name: $name
		E-mail: $emailAddr

		Sending IP:$_SERVER[REMOTE_ADDR]";
		
		//send the email 
		mail('mohamed.maail@gmail.com', $subject, $contactMessage);
		//$_SESSION['msg'] = "Thank you for the feedback.";
		echo('success'); //return success callback
?>