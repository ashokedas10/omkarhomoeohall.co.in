<?php
	

		// for OMKAR   
        $ch = curl_init();        
        curl_setopt($ch, CURLOPT_URL, "http://aitiana.in/Cron/delete_session_files/");       
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        
        $output = curl_exec($ch);      
        curl_close($ch);      
		// for OMKAR	

		


		$to= 'ashokedas@gmail.com';
		$subject = 'All Cron Job Completed for the date'.date('Y-m-d');
		$message ="Deleted All Session Files";			
		$headers = 'From: Omkar<info@aitiana.in>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $message, $headers);

?>