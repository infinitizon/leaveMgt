<?php
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	$headers .= 'From: National Institute for Sports <no-reply@nisports.gov.ng>' . "\r\n";
	$headers .= 'Bcc:info@nisports.gov.ng' . "\r\n";
	
	$toMsg = "Thank you for subscribing to our newsletter.\r\n<br />";
	$toMsg .= "You will get newsletters as emails in your box.\r\n<br /><br /><br />";
	$toMsg .= "<small>This is an antomatically generated email in response to your newsletter subscription, ";
	$toMsg .= "so you cannot reply!<br />";
	$toMsg .= "If you wish to unsbscribe from this newsletter, ";
	
	if(mail("test4@bimbo.com", "Subscription to NIS newsletter", $toMsg, $headers)) {
		echo 'Your email has been successfully subscribed to the newsletter service.<br /><br /><br />Thank you!!';
	} else {
		echo "Sorry . We could not subscribe you at this time.<br /><br />Please check back at a later time.";
	}
echo sha1( "j^i207soif5+_7~%4*%03dql" . md5("123" . "j^i207soif5+_7~%4*%03dql"))
?>