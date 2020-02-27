<html>
<head>
	<title>ZK Test</title>
</head>
<body>
<?php
print_r($_SERVER);
$servername = "localhost";
$username = "hrm";
$password = "QL#M-ib6";
$dbname = "workablezone";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
if(@$_GET['sync']=='attendance'){
	$ip=$_GET['ip'];
	$your_ip = $ip;
}else if(@$_GET['sync']=='attendancefrom'){
	$ip=$_GET['ip'];
	$your_ip = $ip;
}else if(@$_GET['sync']=='dipattendance'){
	$ip='192.168.0.4';
	$your_ip = $ip;
}else if(@$_GET['sync']=='driverattendance'){
	$ip='35.195.18.195';
	$your_ip = $ip;
}
if(@$_GET['sync']=='dipattendance'){
	// 'date-range=230917-230917';
	echo $today_dates=date('dmy');
	echo "<br>";
	echo $previous_date=date('dmy', strtotime('-1 day', strtotime(date('Y-m-d'))));
	$url='http://sa:Admin14@192.168.0.4/COSEC/api.svc/attendance-daily?action=get;date-range=011117-011217;format=xml;format=xml;field-name=userid,username,workingshift,weekoffandholiday,shiftstart,shiftend,processdate,scheduleshift,punch1,punch2,overtime';
	//echo $url="http://sa:Admin14@192.168.0.4/COSEC/api.svc/attendance-daily?action=get;date-range=$previous_date-$today_dates;format=xml;field-name=userid,username,workingshift,weekoffandholiday,shiftstart,shiftend,processdate,scheduleshift,punch1,punch2,overtime";

	$xml_string = file_get_contents($url);
	$xml = new SimpleXMLElement(utf8_encode($xml_string));
	$xml = (array)$xml;
	$value_array=$xml['attendance-daily'];
}
else if(@$_GET['sync']=='driverattendance'){
	$driver_servername = "35.195.18.195";
	$driver_username = "fivetran";
	$driver_password = "1YsLnTNcKuuh%0jkv9I1g";
	$driver_dbname = "awok_logistics";
	// Create connection
	$conn1 = new mysqli($driver_servername, $driver_username, $driver_password, $driver_dbname);
	// Check connection
	if ($conn1->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$today_date=date('Y-m-d');
	$previous_date=date('Y-m-d', strtotime('-1 day', strtotime($today_date)));
	if((@$_GET['datefrom']) && (@$_GET['dateto'])){
		$datefrom=date('Y-m-d', strtotime($_GET['datefrom']));
		$dateto=date('Y-m-d', strtotime($_GET['dateto']));
		$dates = " and date(h.created_at) >='".$datefrom."'  and  date(h.created_at)<='".$dateto."' ";
	}
	else{
		$dates = " and date(h.created_at) = '".$previous_date."'";
	}
	$sql="select date(action_date) as actiondate, driver, emp_driver_id,email,
 count(distinct if(date(action_date)=date(verified_at) and status_id=18 ,shipment, null)) as assigned_count,
count(if(fulfilled =1,1,null)) as delivered_cnt, 
count(if(returned = 1,1,null)) as cancelled_cnt,
round((count(if(returned = 1,1,null))/(count(if(returned = 1,1,null)) +count(if(fulfilled =1,1,null))))*100, 2) as cancel_percent
from (

select a.emp_driver_id, b.email,concat(b.name,' ', b.last_name) as driver,
a.fulfilled,a.returned, a.id as shipment,status_id,a.verified_at,
max(h.created_at) as action_date
from awok_logistics.aw_shipment_history_log h 

join awok_logistics.aw_shipment a
on a.id=h.shipment_id join awok_logistics.aw_user_courier c on c.user_id=a.emp_driver_id and c.courier_id=6
left join awok_logistics.aw_user b on a.emp_driver_id=b.id
where  a.status_id=18 $dates
and JSON_UNQUOTE(JSON_EXTRACT(h.data , '$.status_id')) in ('4','5','21') and h.type ='status_change'

group by 1,2,3,4,5,6,7
 ) tab 
 group by 1,2,3
  order by 1";


	$result = mysqli_query($conn1,$sql);

	echo '<table border="1" cellpadding="5" cellspacing="2">
            <tr>
                <th colspan="7">Data Attendance</th>
            </tr>
            <tr>         
                <th>Date</th>
                <th>Driver Name</th>
                <th>Driver Id</th>
                <th>Assigned Count</th>
				<th>Delivered Count</th>
				<th>Cancelled Count</th>
				<th>Cancel Rate</th>
            </tr>';
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$date_only=date("Y-m-d", strtotime($row['actiondate']));

			//if(strtotime($date_only) >= strtotime($today_date)){

			$conn->query("delete from xin_driver_attendance_synch where driver_id='".$row['emp_driver_id']."' and attendance_date='".$row['actiondate']."'");


			$sql1 = "INSERT INTO xin_driver_attendance_synch (driver_id,driver_name,driver_email,attendance_date,assigned_count,delivery_count,cancellation_count,cancellation_rate) VALUES('".$row['emp_driver_id']."','".$row['driver']."','".$row['email']."','".$row['actiondate']."','".$row['assigned_count']."','".$row['delivered_cnt']."','".$row['cancelled_cnt']."','".$row['cancel_percent']."')";
			//echo "<br>";
			$conn->query($sql1);



			echo '<tr>
                <td>'.$row['actiondate'].'</td>
				<td>'.$row['driver'].'</td>
				<td>'.$row['emp_driver_id'].'</td>
				<td>'.$row['assigned_count'].'</td>
				<td>'.$row['delivered_cnt'].'</td>
				<td>'.$row['cancelled_cnt'].'</td>
				<td>'.$row['cancel_percent'].'</td>
           
            </tr>';

			//}

		}
	}
	//mail("siddiq.jalalu@gmail.com","Attendance Cron-".$your_ip,"Attendance Insertion Completed");

	echo '</table>';







}else{

	$options = array(
		'location' => 'http://' . $your_ip . '/iWsService',
		'uri' => 'http://www.zksoftware/Service/message/'
	);

	$client = new SoapClient(null, $options);

	if(@$_GET['sync']=='attendance'){
		$soapRequest ="<GetAttLog><ArgComKey>0</ArgComKey><Arg></Arg></GetAttLog>";
	}else if(@$_GET['sync']=='attendancefrom'){
		$soapRequest ="<GetAttLog><ArgComKey>0</ArgComKey><Arg></Arg></GetAttLog>";
	}


	$response = $client->__doRequest($soapRequest, 'http://' . $your_ip . '/iWsService', '', '1.1');
	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
	$xml = simplexml_load_string($xml);
	$json = json_encode($xml);
	$responseArray = json_decode($json,true);
	$value_array=$responseArray['Row'];
	echo "<pre>";print_r($value_array);die;
}

?>
<?php if(@$_GET['sync']=='attendance'){?>
	<table border="1" cellpadding="5" cellspacing="2">
		<tr>
			<th colspan="6">Data Attendance</th>
		</tr>
		<tr>

			<th>Biometric ID</th>
			<th>Status</th>
			<th>Check In/Out Date</th>
			<th>Check In/Out Time</th>
			<th>IP</th>
			<th>Check In/Out</th>
		</tr>
		<?php
		//mail("siddiq.jalalu@gmail.com","Attendance Cron-'".$ip."'","Going To Run Attendance");
		$today_date=date('Y-m-d');

		//echo "<pre>";print_r($v_arry);die;
		foreach($value_array as $v_arry){


			$date_only=date("Y-m-d", strtotime($v_arry['DateTime']));

			if(strtotime($date_only) >= strtotime($today_date)){ // > '2017-07-19' == $today_date 2017-07-25

				//$conn->query("delete from xin_biometric_attendance_synch where check_in_date='".$v_arry['DateTime']."' and ip_address='".$ip."' and biometric_id='".$v_arry['PIN']."'");

				$d=date("Y-m-d",strtotime($v_arry['DateTime']));
				$t=date("H:i:s",strtotime($v_arry['DateTime']));

				//echo $sql = "INSERT INTO xin_biometric_attendance_synch (biometric_id,status,check_in_out_date,check_in_out_time,ip_address,check_in_date,WorkingShift,scheduleshift,shiftstart,shiftend,process_date) VALUES('".$v_arry['PIN']."','".$v_arry['Status']."','".$d."','".$t."','".$ip."','".$v_arry['DateTime']."','','','','','".$date_only."')";

				//echo "<br>";
				//$conn->query($sql);

				?>

				<tr>
					<td><?php echo $v_arry['PIN']; ?></td>
					<td><?php echo $v_arry['Status']; ?></td>
					<td><?php echo $d; ?></td>
					<td><?php echo $t; ?></td>
					<td><?php echo $ip; ?></td>
					<td><?php echo $v_arry['DateTime']; ?></td>
				</tr>

			<?php  }

		}
		//mail("siddiq.jalalu@gmail.com","Attendance Cron-".$your_ip,"Attendance Insertion Completed");
		?>
	</table>


<?php }  else if(@$_GET['sync']=='dipattendance'){?>
	<table border="1" cellpadding="5" cellspacing="2">
		<tr>
			<th colspan="6">Data Attendance</th>
		</tr>
		<tr>

			<th>Biometric ID</th>
			<th>Name</th>
			<th>Status</th>
			<th>Check In Date & Time</th>
			<th>Check Out Date & Time</th>
			<th>IP</th>
			<th>Check In/Out</th>
		</tr>
		<?php
		//mail("siddiq.jalalu@gmail.com","Attendance Cron-'".$ip."'","Going To Run Attendance");
		$today_date=date('Y-m-d');
		//$today_date='2017-09-23';

		foreach($value_array as $v_arry){

			$bio_id=$v_arry->UserID;
			$name=$v_arry->UserName;
			$status=0;
			$WorkingShift=$v_arry->WorkingShift;
			$shiftstart=$v_arry->shiftstart;
			$shiftend=$v_arry->shiftend;
			$scheduleshift=$v_arry->scheduleshift;
			$process_date=str_replace('/', '-',$v_arry->ProcessDate);
			$date_only=date("Y-m-d",strtotime($process_date));
			// $previous_date=date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

			// if(strtotime($date_only) >= strtotime($previous_date)){

			$punch1_date=date("Y-m-d",strtotime(str_replace('/', '-',$v_arry->Punch1)));
			$punch1_time=date("H:i:s",strtotime(str_replace('/', '-',$v_arry->Punch1)));


			$punch2_date=date("Y-m-d",strtotime(str_replace('/', '-',$v_arry->Punch2)));
			$punch2_time=date("H:i:s",strtotime(str_replace('/', '-',$v_arry->Punch2)));


			if($v_arry->Punch1!=''){
				$conn->query("delete from xin_biometric_attendance_synch where check_in_date='".($punch1_date.' '.$punch1_time)."' and ip_address='".$ip."' and biometric_id='".$bio_id."'");

				$sql = "INSERT INTO xin_biometric_attendance_synch (biometric_id,status,check_in_out_date,check_in_out_time,ip_address,check_in_date,WorkingShift,scheduleshift,shiftstart,shiftend,process_date) VALUES('".$bio_id."','P1','".$punch1_date."','".$punch1_time."','".$ip."','".($punch1_date.' '.$punch1_time)."','".$WorkingShift."','".$scheduleshift."','".$shiftstart."','".$shiftend."','".$date_only."')"; echo "<br>";
				$conn->query($sql);
			}

			if($v_arry->Punch2!=''){

				$conn->query("delete from xin_biometric_attendance_synch where check_in_date='".($punch2_date.' '.$punch2_time)."' and ip_address='".$ip."' and biometric_id='".$bio_id."'");

				$sql = "INSERT INTO xin_biometric_attendance_synch (biometric_id,status,check_in_out_date,check_in_out_time,ip_address,check_in_date,WorkingShift,scheduleshift,shiftstart,shiftend,process_date) VALUES('".$bio_id."','P2','".$punch2_date."','".$punch2_time."','".$ip."','".($punch2_date.' '.$punch2_time)."','".$WorkingShift."','".$scheduleshift."','".$shiftstart."','".$shiftend."','".$date_only."')";echo "<br>";
				$conn->query($sql);
			}


			?>

			<tr>
				<td><?php echo '`'.$bio_id; ?></td>
				<td><?php echo $name; ?></td>
				<td><?php echo $status; ?></td>
				<td><?php echo $punch1_date.' '.$punch1_time; ?></td>
				<td><?php echo $punch2_date.' '.$punch2_time; ?></td>
				<td><?php echo $ip; ?></td>
				<td><?php echo $date_only; ?></td>
			</tr>

			<?php  //}
		}
		//mail("siddiq.jalalu@gmail.com","Attendance Cron-".$your_ip,"Attendance Insertion Completed");
		?>
	</table>


<?php } else if(@$_GET['sync']=='attendancefrom'){?>
	<table border="1" cellpadding="5" cellspacing="2">
		<tr>
			<th colspan="6">Data Attendance</th>
		</tr>
		<tr>

			<th>Biometric ID</th>
			<th>Status</th>
			<th>Check In/Out Date</th>
			<th>Check In/Out Time</th>
			<th>IP</th>
			<th>Check In/Out</th>
		</tr>
		<?php
		//mail("siddiq.jalalu@gmail.com","Attendance Cron-'".$ip."'","Going To Run Attendance");
		$today_date=date('Y-m-d');
		$previous_date=date('Y-m-d');

		//$today_date='2017-09-04';
		//$conn->query("delete from xin_biometric_attendance_synch where check_in_out_date='".$previous_date."' and ip_address='".$ip."'");
		foreach($value_array as $v_arry){

			$date_only=date("Y-m-d", strtotime($v_arry['DateTime']));

			if(strtotime($date_only) >= strtotime($previous_date)){ // > '2017-07-19'

				$conn->query("delete from xin_biometric_attendance_synch where check_in_date='".$v_arry['DateTime']."' and ip_address='".$ip."' and biometric_id='".$v_arry['PIN']."'");

				$d=date("Y-m-d",strtotime($v_arry['DateTime']));
				$t=date("H:i:s",strtotime($v_arry['DateTime']));

				$sql = "INSERT INTO xin_biometric_attendance_synch (biometric_id,status,check_in_out_date,check_in_out_time,ip_address,check_in_date,WorkingShift,scheduleshift,shiftstart,shiftend,process_date) VALUES('".$v_arry['PIN']."','".$v_arry['Status']."','".$d."','".$t."','".$ip."','".$v_arry['DateTime']."','','','','','".$date_only."')";
				$conn->query($sql);

				?>

				<tr>
					<td><?php echo $v_arry['PIN']; ?></td>
					<td><?php echo $v_arry['Status']; ?></td>
					<td><?php echo $d; ?></td>
					<td><?php echo $t; ?></td>
					<td><?php echo $ip; ?></td>
					<td><?php echo $v_arry['DateTime']; ?></td>
				</tr>

			<?php  }
		}
		//mail("siddiq.jalalu@gmail.com","Attendance Cron-".$your_ip,"Attendance Insertion Completed");
		?>
	</table>


<?php } ?>


</body>
</html>
