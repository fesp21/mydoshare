﻿<?php
include('php/_Config.php');
require("php/sendgrid-php/sendgrid-php.php");
$sendgrid = new SendGrid('SendGridKey');
$id = $_GET['id'];
$emailst = "";
$sql1 = "SELECT * FROM _doshare_nogrp WHERE tkey='$id'";
                $result = $conn->query($sql1);
                if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
                                $myname = $row["myname"];
								$myuser = $row["username"];
								$myemail = $row["email"];
								$mygroup = $row["groupn"];
									
										 $grp1 = $mygroup."_bills";
										 $grp2 = $mygroup."_expense";
										 $mysql1 = "ALTER TABLE $grp1 ADD COLUMN $myuser FLOAT(2,2) ;";
										 $mysql2 = "ALTER TABLE $grp2 ADD COLUMN $myuser FLOAT(2,2) ;";
										 $mysql3 = "UPDATE `_doshare_groups` SET groupn='$mygroup' WHERE email='$myemail';";
										 $conn->query($mysql1);
										 $conn->query($mysql2);
										 $conn->query($mysql3);
										 $sendemail = new SendGrid\Email();
									     $sendemail
											->addTo("$myemail")
											->setFrom('noreply@mydoshare.com')
											->setFromName('mydoShare')
											->setSubject("Welcome to $mygroup in mydoshare")    
											->setText('Please enable HTML to view this email')
											->setHtml("<!DOCTYPE HTML><html><meta charset='utf-8'><head><title>mydoShare Alerts</title><link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'><link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'></head><body style='background: #d2d6de;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;'><br><div class='introtitle'><a href='http://www.mydoshare.com' target='_blank' style='float:left;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 35px;background: transparent;color: #444;'><img src='http://www.mydoshare.com/images/mydoshare.PNG' /></a></div><div class='toprightcnt' > <a href='mailto:info@mydoshare.com' style='float: right;display: inline-block;font: \"Play\",sans-serif;text-decoration:none;font-weight: 300;font-size: 15px;background: transparent;color: #444;'>info@mydoshare.com</a></div><br><div style='width: 80%;margin: 7%'><div class='msgbody' style='background: #fff;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;padding:20px;'>Dear $myname,<br><br> Welcome to mydoShare, we are so happy to say you that you request to join $mygroup has been accepted and you now a part of sharegroup. <br><br>Happy mydoSharing!<br><br><br><br><div align='center'></div><br><br>Regards,<br>mydoShare.<br></div></div><div class='downloadapp' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'><h4>Download our app today</h4><a href='https://play.google.com/store/apps/details?id=io.gonative.android.kqazb&hl=en' target='_blank'><img width='120' height='40' title='' alt='' src='http://www.mydoshare.com/images/googleplay.png' /></a></div><div class='socialmedia' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'> <h4>Connect with us on Facebook</h4><a href='https://www.facebook.com/mydoshare'><img width='30' height='30' title='' style='border:1px grey solid' alt='' src='http://www.mydoshare.com/images/fb.png' /></a></div><br><br><br><br><br><br><br><br><div class='footer1' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: left;display: inline-block;'>Copyright &copy; 2016,<br>mydoShare.com</div><div class='footer2' style='-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: \"Source Sans Pro\", \"Helvetica Neue\", Helvetica, Arial, sans-serif;font-weight: 400;float: right;display: inline-block;'>This is autogenerated email,<br>don't reply to this.</div></body></html>");
										$res = $sendgrid->send($sendemail);
										$sql3 = "UPDATE _doshare_nogrp SET tkey=00090433946330009600786815 WHERE tkey='$id';";
										$rst1 = $conn->query($sql3);
										$sql4 = "DELETE from _doshare_nogrp WHERE tkey='$id';";
										$rst2 = $conn->query($sql4);
						               	$emailst = "Thank You for approving your friend's request to join $mygroup!<br>";
						            
                    }
                        
                }
				else
				{
					$emailst = "Something went wrong! <br> Contact us at info@mydoshare.com";
				}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Email Verification | mydoShare</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="http://www.mydoshare.com/app/bootstrap/css/bootstrap.min.css" lazyload>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" lazyload>
    <link rel="stylesheet" href="http://www.mydoshare.com/app/dist/css/ionicons.min.css" lazyload>
	 <script src="http://www.mydoshare.com/app/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="http://www.mydoshare.com/app/dist/css/AdminLTE.min.css" lazyload>
    <link rel="stylesheet" href="http://www.mydoshare.com/app/plugins/iCheck/square/blue.css" lazyload>
	<link href='https://fonts.googleapis.com/css?family=Play|Shadows+Into+Light|Pacifico|Orbitron|Dancing+Script|Kaushan+Script' rel='stylesheet' type='text/css' lazyload>
<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
 <script>
	$(window).load(function() {
		$(".se-pre-con").fadeOut("slow");;
	});
</script>

    <!--[if lt IE 9]>
        <script async src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script async src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
  <div class="se-pre-con"></div>
    <div class="login-box">
      <div class="login-logo">
        <a href="index.php">mydoShare</a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">&nbsp;</p>
        <div align="center"><?php echo $emailst;?></div>
        <br>
        <!--<a href="#">I forgot my password</a>--><br>
      </div>
    </div>
        <div align="center">
         <small>இது ஒரு இந்தியனின் படைப்பு</small> 
		 </div>
    <script src="http://www.mydoshare.com/app/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="http://www.mydoshare.com/app/bootstrap/js/bootstrap.min.js"></script>
    <script src="http://www.mydoshare.com/app/plugins/iCheck/icheck.min.js"></script>	
	<script src="http://www.mydoshare.com/app/dist/js/async.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%'
        });
      });
    </script>
  </body>
</html>
