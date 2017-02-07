<?php
session_name('doShare');
session_start();
include('php/_Config.php');
include('php/_Update.php');
include('php/alertsendgrid.php');
$myname = $myemail = "";
$myinfo = $_SESSION["user"];
$grpid = $_SESSION["gpname"];
$grp1 = $grpid."_bills";
$grp2 = $grpid."_expense";
$grpid = $_SESSION["gpname"];
$demoid= $_SESSION["bill"];
$billid = $demoid; 
$expvals =  array();
$billvals =  array();
$expcnt = 0;
$billcnt = 0;
if($_SESSION["user"]!="")
{
   $sql = "SELECT * FROM _doshare_users WHERE email='$myinfo'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    $myname = $row["name"];
                    $myemail = $row["email"];
                                $myusr = $row["username"];
            }
                }
				$base = "Select billname,total from $grp1 WHERE id=$billid";
									$base_result = $conn->query($base);
									if ($base_result->num_rows > 0){
										while($base_row = $base_result->fetch_assoc()) {
											$billnametemp = $base_row['billname'];
											$totaltemp = $base_row['total'];
										}
									}
				$grp = $_SESSION["gpname"];
									$get = "SELECT uname from `_doshare_groups` WHERE groupn='$grp'";
                                     $result = $conn->query($get);
                                        if ($result->num_rows > 0) {
                                                 while($row = $result->fetch_assoc()) {
													   $cuserval = $row['uname'];
														   $expid = "select $cuserval as username from $grp2 where id=$billid";
													   $exprslt = $conn->query($expid);
													   if ($exprslt->num_rows > 0) {
														   while($exprow = $exprslt->fetch_assoc()) {
															   $expvals[$expcnt]=$exprow['username'];
															   $expcnt++;
														   }
													   }
													   $billsql = "select $cuserval as username from $grp1 where id=$billid";
													   $billrslt = $conn->query($billsql);
													   if ($billrslt->num_rows > 0) {
														   while($billrow = $billrslt->fetch_assoc()) {
															   $billvals[$billcnt]=$billrow['username'];
															   $billcnt++;
														   }
													   }
                                                       
                                                    }
                                            }
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
		$billid = $_POST["billnameval"];
        $total = $_POST["amount"];
        $bill = $_POST["billname"];
		sendalert("edited",$bill,$total,$grpid,$myname);
        $ins = "UPDATE $grp1 SET billname='$bill',total=$total where id=$billid";
        $inst = "UPDATE $grp2 SET billname='$bill',total=$total where id=$billid";
		echo $ins;
        if (($conn->query($ins) === TRUE)&&($conn->query($inst) === TRUE))
        {
        $get = "SELECT name,uname from `_doshare_groups` WHERE groupn='$grpid'";
        $result = $conn->query($get);
        if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
                $name= $row["uname"];
                $ename = $name.'e';
                if(isset($_POST["$ename"]))
				{
					$expense = $_POST["$ename"];
				}
				else{
					$expense=0;
				}
                if(isset($_POST["$name"]))
				{
					$bills = $_POST["$name"];
				}
				else{
					$bills=0;
				}
                update($grpid,$name,$expense,$bills,$billid);
        }
        }
        else
        {
            $myname = "err";
        }
        }
        else
        {
            $myname1 = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>
<!DOCTYPE html>
<meta charset="utf-8">
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Expense | mydoShare</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" lazyload>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" lazyload>
    <link rel="stylesheet" href="dist/css/ionicons.min.css" lazyload>
	<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css" lazyload>
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css" lazyload>
	<link href='https://fonts.googleapis.com/css?family=Play|Shadows+Into+Light|Pacifico|Orbitron|Dancing+Script|Kaushan+Script' rel='stylesheet' type='text/css' lazyload>
	 
<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
 <script>
	$(window).load(function() {
		$(".se-pre-con").fadeOut("slow");;
	});
	var myuserarray = new Array();
	var userenabledarray = new Array();
	var usercnt=0;
</script>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
  <div class="se-pre-con"></div>
    <div class="wrapper">
      <header class="main-header">
        <a href="index.php" class="logo">
          <span class="logo-mini"><b>do</b></span>
          <span class="logo-lg">mydoShare</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class=""><?php echo $myname; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-footer">
                    <p>
                     <div align="center"><?php echo $myname; ?><br>
                      <small><?php echo $myemail; ?></small>
                    </div>
                    </p>
					<div class="pull-left">
                      <a onclick="leavegroup()" class="btn btn-default btn-flat">Leave Group</a>
                    </div>
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <aside class="main-sidebar">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image">
              <img src="dist/img/profile.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $myname; ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>  
            </div>
          </div>
         <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<li><a href="share.php"><i class="fa fa-edit"></i> <span>Share Your Expense</span></a></li>
			<li  class="active"><a href="delete.php"><i class="fa fa-times"></i> <span>Edit/Delete Expense</span></a></li>
            <li><a href="showbalance.php"><i class="fa fa-pie-chart"></i> <span>Show Balance</span></a></li>
            <li><a href="sendemail.php"><i class="fa fa-envelope-o"></i> <span>Send Email</span></a></li>		
          </ul>
        </section>
      </aside>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Edit Expense
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
             <li class="active">Edit Expense</li>
          </ol>
        </section>
        <section class="content">
          <div class="row" >
            <div class="col-md-12" >
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit Expense</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                    <form method="post" id="share" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<input type="text" name="billnameval" value="<?php echo $demoid ?>" style="display:none">
                            <table>                                
                                <tbody id="content">
                                    <tr>
                                        <td style="padding:25px;"><div class="form-group"></div></td>
                                        <td style="padding:10px;"><input type="text"class="form-control" placeholder="Enter Bill Name" name="billname" value="<?php echo $billnametemp?>" required /></td>
                                        <td style="padding:10px;"><input type="text"class="form-control" placeholder="Enter Total Amount" value="<?php echo $totaltemp?>" onchange="calc()" name="amount" id="amount" required /></td>
                                    </tr>
									<tr>
                                        <td style="padding:10px;"><input type="text"class="form-control" value="Name" disabled /></td>
                                        <td style="padding:10px;"><input type="text"class="form-control" value="Paid Amount" disabled /></td>
                                        <td style="padding:10px;"><input type="text"class="form-control" value="Expense Amount" disabled /></td>
                                    </tr>
                                    <?php
                                    $grp = $_SESSION["gpname"];
									$cnt=0;
									$expcnt =0;
									$billcnt = 0;
									$get = "SELECT name,uname from `_doshare_groups` WHERE groupn='$grp'";
                                     $result = $conn->query($get);
                                        if ($result->num_rows > 0) {
                                                 while($row = $result->fetch_assoc()) {
                                                        ?>
                                                            <tr><td><div style="display:inline-block"><input type="checkbox" style="float:right;margin-right: 5px;" name="<?php echo $row["uname"].'c';?>" onchange="check('<?php echo $row["uname"];?>',<?php echo $cnt;?>)" id="<?php echo $row["uname"].'c';?>" checked /></div><div style="display:inline-block"><input type="text" class="form-control" value="<?php echo $row["name"];?>" disabled /></div></td><td style="padding:10px;"><input type="number" step="any" class="form-control" value="<?php echo $billvals[$billcnt]?>" name="<?php echo $row["uname"];?>" id="<?php echo $row["uname"];?>" required /></td><td style="padding:10px;"><input type="number" step="any" class="form-control" value="<?php echo $expvals[$expcnt]?>" name="<?php echo $row["uname"].'e';?>" id="<?php echo $row["uname"].'e';?>" required/></td></tr>
													    <script>
														myuserarray[usercnt]=<?php echo $row["uname"];?>;
														usercnt++;
														</script>
													   <?php
														$expcnt++;
														$billcnt++;
														$cnt++;
                                                    }
                                            }
                                    ?>
                                          <tr>
									<td style="padding:10px;"></td>
									<td style="padding:10px;"><input type="submit" id="b2" class="btn btn-primary btn-block btn-flat" value="Edit Expense" /></td>
									<td style="padding:10px;"></td>
									</tr>
                             </tbody>
                            </table>
                        </form>                  
                    </div><br><br>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <small>Beta</small>
        </div>
         <small>இது ஒரு இந்தியனின் படைப்பு</small>
      </footer>     
      <div class="control-sidebar-bg"></div>
    </div>
   
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/app.min.js"></script>
	<script src="plugins/iCheck/icheck.min.js"></script>
	<script src="plugins/iCheck/icheck.min.js"></script>
	<script src="dist/js/async.js"></script>
		<script>
		/*$(document).ready(function(){
		if(document.URL.indexOf("#")==-1)
		{
			url = document.URL+"#";
			location = "#";
			location.reload(true);
		}
		});*/
		var totfinamt=0;
		var totbillamt=0;
		var f=0;
		var b=0;
		$(document).ready(function() {
		$('#share').on('submit', function(e){
			totfinamt=0;
			totbillamt=0;
			f=0;
			b=0;
			for(var i=0;i<myuserarray.length;i++)
				{
					if(userenabledarray[i]==1)
					{
						totfinamt=parseFloat(totfinamt)+parseFloat(document.getElementById(myuserarray[i].id+'e').value);
						totbillamt=parseFloat(totbillamt)+parseFloat(document.getElementById(myuserarray[i].id).value);
					}
				}
				var camtint = document.getElementById("amount").value;
				var valid=false;
				if(parseInt(totfinamt)!=parseInt(camtint))
				{
					alert('We think you have an error in your expense - Total bill amount '+parseInt(camtint)+' is not equal to Total expense amount '+parseInt(totfinamt));
				}
				else
				{
					f=1;
				}
			    if(parseInt(totbillamt)!=parseInt(camtint))
				{
					alert('We think you have an error in your expense - Total bill amount '+parseInt(camtint)+' is not equal to Total paid amount '+parseInt(totbillamt));
				}
				else
				{
					b=1;
				}
				if((f==1)&&(b==1))
				{
					valid=true;
				}
			if(!valid) {
		e.preventDefault();
		}
	});
	});
	function leavegroup()
	{
		$.confirm({
        text: "Are you sure want to leave this share group?",
        confirm: function(button) {
             $.ajax({
                url: 'php/leave.php',
				success:function(response) {
                         if(response=="success")
							 location.reload();
						 else
							  location.reload();
                }
			});
        },
        cancel: function(button) {
        }
    });
	}
	</script>
	<?php
  include('php/analytics.php');
  ?>
    <script type="text/javascript">
    var c=0;
    var famount = 0;
	var ctot=0;
	for(var i=0;i<myuserarray.length;i++)
		userenabledarray[i]=1;
	function check(name,id)
	{
		var cbox=name;
		name=name+'c';
		if(document.getElementById(name).checked) {
				userenabledarray[id]=1;
		} else {
				userenabledarray[id]=0;
		}
		ctot=0;
    for(var i=0;i<myuserarray.length;i++)
		{
			if(userenabledarray[i]==1)
			ctot+=1;	
		}
		var tamount = parseFloat(document.getElementById("amount").value).toFixed(2);
		var camount = parseFloat(tamount/ctot).toFixed(2);
		for(var i=0;i<myuserarray.length;i++)
		{
			if(userenabledarray[i]==1)
			{
				document.getElementById(myuserarray[i].id+'e').value=camount;
			}
			else{
				document.getElementById(myuserarray[i].id+'e').value=0;
				document.getElementById(myuserarray[i].id).value=0;
			}
		}
		if(userenabledarray[id]==1)
		{
			document.getElementById(cbox).disabled = false;
			document.getElementById(cbox+'e').disabled = false;
		}
		else
		{
			document.getElementById(cbox).disabled = true;
			document.getElementById(cbox+'e').disabled = true;
		}
	}

   
            function calc()
            {
                var amount = document.getElementById("amount").value;
                famount = document.getElementById("amount").value;; 
                document.getElementById("<?php echo $myusr ?>").value = amount;
				setcal();
            }
            function setcal()
            {
				var tamt = parseFloat(document.getElementById("amount").value).toFixed(2);
				var tcnt=0;
				for(var i=0;i<myuserarray.length;i++)
					{
						if(userenabledarray[i]==1)
							tcnt+=1;	
					}
				var camt = parseFloat(tamt/tcnt).toFixed(2);
				for(var i=0;i<myuserarray.length;i++)
				{
					if(userenabledarray[i]==1)
			{
				document.getElementById(myuserarray[i].id+'e').value=camt;
			}
			else{
				document.getElementById(myuserarray[i].id+'e').value=0;
			}
				}
            }
            </script>
  </body>
</html>
<?php
}
else
{
  header('Location:login.php');
}
?>