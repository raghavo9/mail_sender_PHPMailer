<?php
 include 'includes.php';
 $display_block=NULL;
 if (!$_POST) {
 //haven’t seen the form, so display it
 $display_block = <<<END_OF_BLOCK
 <div style="background-color:#696969;border-radius:20px; padding:10px; opacity:80%;">
 <form method="POST" action="$_SERVER[PHP_SELF]" enctype="multipart/form-data">

 <p><label for="subject">Subject:</label><br/>
 <input type="text" id="subject" name="subject" size="40" /></p>

 <p><label for="message">Mail Body:</label><br/>
 <textarea id="message" name="message" cols="50 rows="10"></textarea></p>
 <label for="name">File:</label>
 <input name="file[]" multiple="multiple" class="form-control" type="file" id="file">
 <button type="submit" name="submit" value="submit">Submit</button>
 </form>
 </div>
 END_OF_BLOCK;
 } else if ($_POST) {

    if (($_POST['subject'] == "") || ($_POST['message'] == "")) {
         header("Location: sendmail.php");
         exit;
     }
        
         
         dbcon();
        
         if (mysqli_connect_errno()) {
         
         printf("Connect failed: %s\n", mysqli_connect_error());
         exit();
         } else {
         
         $sql = "SELECT `email` FROM subscribers";
         $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
         
         require 'PHPMailerAutoload.php';
         require 'credentials.php';
         
        
         while ($row = mysqli_fetch_array($result)) {
         set_time_limit(0);
         $email = $row['email'];

         $mail = new PHPMailer;
         $mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = EMAIL;                 // SMTP username
			$mail->Password = PASSWORD;                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->setFrom(EMAIL, 'RAGHAV_RASTOGI');
			$mail->addAddress($email);     // Add a recipient

			$mail->addReplyTo(EMAIL);
            
            for ($i=0; $i < count($_FILES['file']['tmp_name']) ; $i++) { 
				$mail->addAttachment($_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]);    // Optional name
			}
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $_POST['subject'];
			$mail->Body    = $_POST['message'];
			$mail->AltBody = $_POST['message'];

			if(!$mail->send()) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
                echo 'Message has been sent<br>';
                $display_block .= "newsletter sent to: ".$email."<br/>";
			}
        }
    }
}



 
        ?>
         <!DOCTYPE html>
         <html>
         <head>
         <title>Sending a Newsletter</title>
         </head>
         <body style="background-image: url('good_bg.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: cover;color:white;">
         
         <div style="border-style:solid; border-width:5px; border-color:yellow; width:600px; border-radius:20px; padding:10px;margin-left:22%; margin-top:14%;">
         <h1>Send a Newsletter</h1>
         <?php echo $display_block; ?>

         </div>
         </body>
         </html>