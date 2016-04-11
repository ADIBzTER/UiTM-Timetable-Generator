<!--
Project : UiTM Timetable Generator
Description : Fetch and generate timetable from iCress
Created by : Afif Zafri
Credit : Mohd Shahril (regex code)
Created Date : 24/1/16
UPDATE 10/4/16
Add Timetable.js javascript plugin to create responsive timetable.
-->
<html>
<head>
<link href="uitmlogo.png" rel="shortcut icon">
<link rel="stylesheet" type="text/css" href="./styles/design.css">
<link rel="stylesheet" href="./styles/timetablejs.css">
<title>UiTM Timetable Generator</title>
</head>
<body>
<center>

<div class='noprint'>
<table>
<tr>
<td><img src="uitmlogo.png" width="80px"/></td><td><h1>UiTM Timetable<br>Generator</h1></td>
</tr>
</table>

<form action="index.php" method="get">
<h3>Number of subjects : 
<label><select name="numsub">
<?php
$sub = range(1,10);
foreach($sub as $sub)
{
	echo "<option value='$sub'>{$sub}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
}
 ?>
 </select></label></h3>
&nbsp;
<input type="submit" name="submit" class="myButton"><br><br>
</form>

<?php

if(isset($_GET['submit']))
{
	$numsub = $_GET['numsub'];
	
	echo "
	<font color='red'>Note : Enter your subject code under the Subject column and your group under the Group column.</font><br><br>
	
	<form action='index.php?numsub=$numsub&submit=Submit' method='post'>
	
	<table class='newtable'>
	<tr>
	<th>#</th>
	<th>Subject</th>
	<th>Group</th>
	</tr>
	";
	

	for($i=0;$i<$numsub;$i++)
	{
		$num = $i+1;
		echo "
		<tr>
		<td>$num.</td>
		<td><input type='text' name='sub$i'></td><td><input type='text' name='group$i'></td>
		</tr>
		";
	}
	
	echo "
	</table>
	<br><br>
	<label>
	<select name='fakulti'>
	<option value=''>---SELECT FACULTY---</option>
	<option value='AC'>AC-FAKULTI PERAKAUNAN</option>
	<option value='AD'>AD-FAKULTI SENILUKIS DAN SENIREKA</option>
	<option value='AG'>AG-KAMPUS MELAKA</option>
	<option value='AM'>AM-FAKULTI SAINS PENTADBIRAN DAN PENGAJIAN POLISI</option>
	<option value='AP'>AP-FAKULTI SENIBINA PERANCANGAN &amp; UKUR</option>
	<option value='AR'>AR-PERLIS</option>
	<option value='AS'>AS-FAKULTI SAINS GUNAAN</option>
	<option value='AT'>AT-FAKULTI PERLADANGAN DAN AGROTEKNOLOGI</option>
	<option value='BM'>BM-Fakulti Pengurusan Perniagaan Shah Alam</option>
	<option value='BT'>BT-KAMPUS PULAU PINANG</option>
	<option value='CS'>CS-FAKULTI TEKNOLOGI MAKLUMAT DAN SAINS KUANTITATIF</option>
	<option value='CT'>CT-FAKULTI TEKNOLOGI KREATIF &amp; ARTISTIK</option>
	<option value='DU'>DU-KAMPUS TERENGGANU</option>
	<option value='EC'>EC-FAKULTI KEJURUTERAAN AWAM</option>
	<option value='ED'>ED-FAKULTI PENDIDIKAN, UiTM KAMPUS SEKSYEN 17, SHAH ALAM</option>
	<option value='EE'>EE-FAKULTI KEJURUTERAAN ELEKTRIKAL</option>
	<option value='EH'>EH-FAKULTI KEJURUTERAAN KIMIA</option>
	<option value='EM'>EM-FAKULTI KEJURUTERAAN MEKANIKAL</option>
	<option value='HM'>HM-FAKULTI PENGURUSAN HOTEL DAN PELANCONGAN</option>
	<option value='HP'>HP-HEP SHAH ALAM</option>
	<option value='HS'>HS-FAKULTI SAINS KESIHATAN</option>
	<option value='IS'>IS-FAKULTI PENGURUSAN MAKLUMAT</option>
	<option value='JK'>JK-KAMPUS PAHANG</option>
	<option value='KK'>KK-KAMPUS SABAH</option>
	<option value='KP'>KP-Cawangan  N. Sembilan</option>
	<option value='LW'>LW-FAKULTI UNDANG-UNDANG</option>
	<option value='MA'>MA-(UiTM Kelantan [HEA/JW/05-2007)</option>
	<option value='MC'>MC-FAKULTI KOMUNIKASI DAN PENGAJIAN MEDIA</option>
	<option value='MU'>MU-FAKULTI MUZIK</option>
	<option value='OM'>OM-FAKULTI PENGURUSAN DAN TEKNOLOGI PEJABAT</option>
	<option value='PB'>PB-Kampus Shah Alam - Akademi Pengajian Bahasa</option>
	<option value='PH'>PH-FAKULTI FARMASI</option>
	<option value='PI'>PI-PUSAT PEMIKIRAN DAN KEFAHAMAN ISLAM (CITU)</option>
	<option value='SA'>SA-Kampus Kota Samarahan, Sarawak</option>
	<option value='SG'>SG-Kampus Johor</option>
	<option value='SI'>SI-KAMPUS PERAK</option>
	<option value='SP'>SP-Kampus Kedah</option>
	<option value='SR'>SR-FAKULTI SAINS SUKAN DAN REKREASI</option>
	 </select></label><br>
	<br>
	<input type='submit' name='submit2' class='myButton'>
	</form>
	
	</div>
	";


	if(isset($_POST['submit2']))
	{
		$subs = "";
		$sub = "";
		$group = "";
		$fakulti = $_POST['fakulti'];

		for($i=0;$i<$numsub;$i++)
		{
			$sub = $_POST["sub$i"];
			$group = $_POST["group$i"];
			
			//start fetch icress data - credit : Shahril96
			$jadual = file_get_contents("http://icress.uitm.edu.my/jadual/{$fakulti}/{$sub}.html");
			$jadual = str_replace(array("\r", "\n"), '', $jadual);
			preg_match_all('#<td>(.*?)</td>#i', $jadual, $outs);

			$splits = array_chunk(array_splice($outs[1], 7), 7);

			$new = array();

			foreach($splits as $split) {
				$new[$split[0]][] = $split;

				foreach($new[$split[0]] as &$each) {
					unset($each[0]);
				}
			}
			//end fetch icress data
			
			//get array size of group list
			$size = count($new["$group"]);
			//fetch all details from array
			for($j=0;$j<$size;$j++)
			{	
				$s = $new["$group"][$j][1];
				$e = $new["$group"][$j][2];
				
				//change 12 hour format to 24 hour format
				$s2  = date("H:i", strtotime($s));
				$e2  = date("H:i", strtotime($e));
				
				//replace : to ,
				$start_time = str_replace(":" , "," , $s2); 
				$end_time = str_replace(":" , "," , $e2);
				
				$class = $new["$group"][$j][6];
				$day = $new["$group"][$j][3];
				
				//insert data into Timetable.js format, and store into a variable
				$subs .= " timetable.addEvent('". $sub ." - ". $class . "', '". $day ."', new Date(0,0,0,".$start_time."), new Date(0,0,0,".$end_time."), '#'); ";
				
				
			}
			 
			
			
			
		}
		
		
		?>

			<!--Start Generate Timetable -->
			<div class='timetable'></div>
			    
			    <script src='./scripts/timetable.min.js'></script>
			
					<script>
					  var timetable = new Timetable();
			
					  timetable.setScope(8,0)
			
					 timetable.addLocations(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
			
					<?php
					echo $subs;
					?>
			
				  var renderer = new Timetable.Renderer(timetable);
					  renderer.draw('.timetable');
					</script>
					
			<!--End Generate Timetable -->
			
			//print button
			<br><br>
			<div class='noprint'>
				<a href='javascript:window.print()'><button class='myButton'>Print</button></a>
			</div>
			<br><br>
			
		<?php
		
	}
}


?>
		
<br><br><br><br>
<div class="noprint">
Afif Zafri &copy; 2016
</div>
</center>
</body>
</html>
