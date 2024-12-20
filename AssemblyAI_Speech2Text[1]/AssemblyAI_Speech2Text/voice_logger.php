<?php
require("dbconnect_mysqli.php");
require("functions.php");

// $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
// $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];

	
if (isset($_GET["username"]))				{$username=$_GET["username"];
$PHP_AUTH_USER=$username;
$cookie_name = "username";
$cookie_value = $username;
setcookie($cookie_name, $cookie_value, time() + (86400), "/"); // 86400 = 1 day
}elseif (isset($_POST["username"]))	{$username=$_POST["username"];
$PHP_AUTH_USER=$username;
$cookie_name = "username";
$cookie_value = $username;
setcookie($cookie_name, $cookie_value, time() + (86400), "/"); // 86400 = 1 day


}
if (isset($_GET["password"]))				{$password=$_GET["password"];
$PHP_AUTH_PW=$password;
$cookie_name1 = "password";
$cookie_value1 = $password;
setcookie($cookie_name1, $cookie_value1, time() + (86400), "/"); // 86400 = 1 day
}elseif (isset($_POST["password"]))	{$password=$_POST["password"];
$PHP_AUTH_PW=$password;
$cookie_name1 = "password";
$cookie_value1 = $password;
setcookie($cookie_name1, $cookie_value1, time() + (86400), "/"); // 86400 = 1 day

}


$PHP_SELF=$_SERVER['PHP_SELF'];

	
$stmts = "select user_level from vicidial_users where user='$PHP_AUTH_USER';";	
$tabs=mysql_to_mysqli($stmts, $link);
$rows=mysqli_fetch_array($tabs);

$LOGuser_level=$rows[0];



if(isset($_GET['callingText'])) 			{$callingText = $_GET['callingText'];}
elseif(isset($_POST['callingText']))		{$callingText = $_POST['callingText'];}


if(isset($_GET['callingText2'])) 			{$callingText2 = $_GET['callingText2'];}
elseif(isset($_POST['callingText2']))		{$callingText2 = $_POST['callingText2'];}

if(isset($_GET['stationText'])) 			{$stationText = $_GET['stationText'];}
elseif(isset($_POST['stationText']))		{$stationText = $_POST['stationText'];}
if(isset($_GET['talkDuration'])) 			{$talkDuration = $_GET['talkDuration'];}
elseif(isset($_POST['talkDuration']))		{$talkDuration = $_POST['talkDuration'];}

if(isset($_GET['download'])) 			{$download = $_GET['download'];}
elseif(isset($_POST['download']))		{$download = $_POST['download'];}
if(isset($_GET['status'])) 			{$status = $_GET['status'];}
elseif(isset($_POST['status']))		{$status = $_POST['status'];}
if(isset($_GET['checkbox'])) 			{$checkbox = $_GET['checkbox'];}
elseif(isset($_POST['checkbox']))		{$checkbox = $_POST['checkbox'];}
if(isset($_GET['audiodownload'])) 			{$audiodownload = $_GET['audiodownload'];}
elseif(isset($_POST['audiodownload']))		{$audiodownload = $_POST['audiodownload'];}

//echo $callCenter ;

if(isset($_GET['start'])) 			{$start = $_GET['start'];}
elseif(isset($_POST['start']))		{$start = $_POST['start'];}
if(isset($_GET['end'])) 			{$end = $_GET['end'];}
elseif(isset($_POST['end']))		{$end = $_POST['end'];}


if(!isset($_COOKIE['username'])) {
    //echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
   // echo "Cookie '" . $cookie_name . "' is set!<br>";
   // echo "Value is: " . $_COOKIE[$cookie_name];
     $PHP_AUTH_USER=$_COOKIE['username'];
}
if(!isset($_COOKIE['password'])) {
    //echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
   // echo "Cookie '" . $cookie_name . "' is set!<br>";
   // echo "Value is: " . $_COOKIE[$cookie_name];
     $PHP_AUTH_PW=$_COOKIE['password'];
}


if($callingText){$callingFilter = "and vl.phone_number IN($callingText)";}
if($stationText){$stationFilter = "and vu.campaign_id IN($stationText)";}
if($status){$statusFilter = "and vl.status IN($status)";}
//if($callCenter){$phoneFilter = "and vl.phone_number IN($callCenter)";}


$start_date = $start." 00:00:00";
$end_date   = $end." 23:59:59";
//echo $start_date.'/'.$end_date;



$query_string = "callingText=$callingText&start=$start_date&end=$end_date&btnshow=Show&download=$download";

if($checkbox){
	
	if($checkbox=='today')
	{
	    $today=date('Y-m-d');
		$start_date = $today." 00:00:00";
        $end_date   = $today." 23:59:59";
		$check1 = 'checked';
	}
	elseif($checkbox=='week')
	{  $start = date('Y-m-d');
		$lastw = strtotime ( '-6 day' , strtotime ( $start ) ) ;
		$lastw =  date('Y-m-d', $lastw);
	    $date=date('Y-m-d');
		$start_date = $lastw." 00:00:00";
        $end_date   = $date." 23:59:59";
		$check2 = 'checked';
	}
	elseif($checkbox=='month')
	{$start = date('Y-m-d');
	    $lastw = strtotime ( '-30 day' , strtotime ( $start ) ) ;
		$lastw =  date('Y-m-d', $lastw);
	    $date=date('Y-m-d');
		$start_date = $lastw." 00:00:00";
        $end_date   = $date." 23:59:59";
		$check3 = 'checked';
	}

    elseif($checkbox=='year')
	{$start = date('Y-m-d');
	    $lastw = strtotime ( '-365 day' , strtotime ( $start ) ) ;
		$lastw =  date('Y-m-d', $lastw);
	    $date=date('Y-m-d');
		$start_date = $lastw." 00:00:00";
        $end_date   = $date." 23:59:59";
		$check4 = 'checked';
	}
}


////////////////////////////////////////////
if($callingText2!='')

{
	$stmt ="select recording_id ,filename, user, length_in_min, extension, start_time, location from recording_log where filename LIKE '%$callingText2%' and location!=''";
     
}
else {



	$stmt ="select recording_id ,filename, user, length_in_min, extension, start_time, location from recording_log where start_time >= '".$start_date."' and start_time <= '".$end_date."'  and location!=''";
      }
	$rslt = mysql_to_mysqli($stmt, $link);
	$num = mysqli_num_rows($rslt);
 //  echo $stmt;
 						if($num>0)
						{
							$i=1;
							//$call_dur=0;
							while($row = mysqli_fetch_row($rslt))
							{
							//	echo $i;
							$total = $row['4']+$row['5']+$row['6']+$row['7'];
						    $aud = $row[6];
						    $blow = explode('/',$aud);
							$locationA = str_replace('103.10.234.211','66.160.143.73',$row[6]);
							$locationA = str_replace('103.135.36.88','66.160.143.73',$locationA);
						$table .= "<tr class=\"odd gradeX\">
								  <td class=\"center\"> $i </td>
								   <td class=\"center\">$row[0]</td>
                                   <td class=\"center\"> $row[1]</td>
								   <td class=\"center\"> $row[2]</td>
                                   <td class=\"center\"> $row[3]</td>
								   <td class=\"center\"> $row[4]</td>
								   <td class=\"center\"> $row[5]</td>
								   <td class=\"center\"><a href=\"transcribe.php?file=$locationA\" target='new'>TTS Convert</a></td>
								   <td class=\"center\">
								   <audio controls><source src=\"$locationA\" type=\"audio/wav\"></audio>
 </td>
							   </tr>";
							  // echo $table;
							   $i++;
							
							}
							//echo $CSV_lines;
						}
if($audiodownload)
{
$file = $_REQUEST['inputurl']; 
$ipAdd = 'http://'.$_SERVER['REMOTE_ADDR'];
$url = $ipAdd."/RECORDINGS/MP3/".$audiodownload;
header("Content-type: application/x-file-to-save"); 
header("Content-Disposition: attachment; filename=".basename($url)); 
readfile($url);
//exit;	
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Avyukta Intellicall</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="description" content="Avenxo Admin Theme">
    <meta name="author" content="KaijuThemes">
  

<style>
.row{width:100%}
.span2{width:12%; float:left}
.span4{width:13%; float:left}
.span3{width:14%; float:left}
.span1{width:8%; float:left}
.span5{width: 10%; float:left}
</style>

    </head>

    <body class="animated-content skin-blue pace-done">
      <?php




$user_auth=0;
$auth=0;
$reports_auth=0;
$admin_auth=0;
$auth_message = user_authorization($PHP_AUTH_USER,$PHP_AUTH_PW,'REPORTS',1);
if ($auth_message == 'GOOD')
	{$auth=1;$user_auth=1;}


		?>    
       
        <?php include('admin_header.php') ?>
        
   
             <div id="container" class="effect mainnav-full">
	     
                         
                               
                                        <!-- Calendar placeholder--> 
                                        <!-- ============================================ -->
                                       
										
								<div class="col-sm-12 eq-box-sm">
                                    <div class="panel">
                                        <div class="panel-heading">
                                           
                                            <h3 class="panel-title"><b>Voice Logger Report</b></h3>
                                        </div>
										
										 <form class="form" action="" method="get" name="form">
                                            <div class="panel-body">
											  <div class="row">
											    <div class="col-sm-4">
                                                 <div class="form-group">
                                                    
                                                   
														<input type="text" class="form-control" name="start" id="dtp-1" value="<?php echo $start ?>" placeholder="From">
                                                   
                                                 </div>
												</div> 
												  <div class="col-sm-4">
                                                <div class="form-group">
                                                    
                                                   
														<input type="text" class="form-control" name="end" id="dtp-2" value="<?php echo $end ?>" placeholder="To">
                                                    
                                                </div>
												</div>
											
											
												<div class="col-sm-4">
												<div class="form-group">
                                                    
														
															<input type="text"  class="input-small form-control"  name="callingText2" value="<?php echo $callingText ?>" placeholder="Search By Phone Number" />
														
                                                </div>
												</div>
												
											</div>
											 <div class="row">
												<div class="col-sm-12">
													<div class="form-group">
													    <div class="col-sm-9">
														<div class="radio">
															<label class="form-radio form-icon btn btn-default form-text"><input type="radio" name="checkbox" id="checkbox" value="today" <?php echo $check1; ?>> Today </label> 
															<label class="form-radio form-icon btn btn-default form-text"><input type="radio" name="checkbox" id="checkbox2" value="week" <?php echo $check2; ?>> This Week </label>
															<label class="form-radio form-icon btn btn-default form-text"><input type="radio" name="checkbox" id="checkbox3" value="month" <?php echo $check3; ?>> This Month  </label>
															<label class="form-radio form-icon btn btn-default form-text"><input type="radio" name="checkbox" id="checkbox4" value="year" <?php echo $check4; ?>> This Year </label>
															<label class="form-radio form-icon btn btn-default form-text"><input type="radio" name="checkbox" id="checkbox5" value="all" <?php echo $check5; ?>> All </label>
														</div>
														</div>
													</div>	
													<div class="form-group">	
														<div class="col-sm-3 text-right">
															<button class="btn btn-primary" name="btnshow" type="submit">Show</button>
														</div>
													</div>	
												</div>
													</div>
												</div>
											</form>
											</div>
											</div>
											
                                                
                                     
                                     
									
<div class="col-sm-12 eq-box-sm">					
 <div class="panel">
 
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><b>Report</b></h3>
                                    </div>  
									<div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="demo-dt-basic" class="table table-striped table-bordered">
												<thead>
												  <tr>
														<th>S No</th>
														<th>RecordingID</th>
														<th>FileName</th>
														<th>Operator</th>
														<th>Lenght</th>
														<th>Extension</th>
														<th>CallDate</th>
														<th>TTS Convert</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												<?php echo $table; ?>
												</tbody>
											</table>
										   
										</div>
                 
				
			</div>
		
                            </div> <!-- .container-fluid -->
                        </div> <!-- #page-content -->
                   
                  </div>
                                        <!-- ============================================ --> 
                          
	    <?php include("report_section_header2.php"); ?>         
                        </div>
                    </section>
              
								
</div>
<link href="<?php echo base_url ?>/assests/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">

		<script src="<?php echo base_url ?>/assests/js/bootstrap.min.js"></script>
        <!--DataTables [ OPTIONAL ]-->
        <script src="<?php echo base_url ?>/assests/plugins/datatables/media/js/jquery.dataTables.js"></script>
        <script src="<?php echo base_url ?>/assests/plugins/datatables/media/js/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url ?>/assests/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
        <!--DataTables Sample [ SAMPLE ]-->
        <script src="<?php echo base_url ?>/assests/js/demo/tables-datatables.js"></script>		

 <script>

    var picker = new Pikaday(
    {
        field: document.getElementById('dtp-1'),
        firstDay: 1,
        minDate: new Date(2000, 0, 1),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000, 2020],
        bound: true,
    //    container: document.getElementById('container'),
		
		
    });
	var picker = new Pikaday(
    {
        field: document.getElementById('dtp-2'),
        firstDay: 1,
        minDate: new Date(2000, 0, 1),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000, 2020],
        bound: true,
     //   container: document.getElementById('container'),
		
		
    });


    </script>		

	<script src="<?php echo base_url ?>/assests/js/scripts.js"></script>
    

    </body>

<!-- Mirrored from avenxo.kaijuthemes.com/tables-data.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 24 Jun 2015 05:23:09 GMT -->
</html>


								
						
						