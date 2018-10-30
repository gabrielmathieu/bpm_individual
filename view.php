<!--
Zeljko Bareta - IM Associate, Belgrade, Serbia
work e-mail:	BARETA@unhcr.org
private e-mail:	zbareta@gmail.com
mobile:			+38163 366 158
skype: 			zeljko.bareta
-->
<html lang="en">
	<head>
		<?php include("functions.php");?>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>BPM-IMS Individual Form</title>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
		<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" class="init">
		$(document).ready(function() {
		$('#data').DataTable();
		} );
		</script>
		<style>
			@media print {
	 	 	#printPageButton {
	    	display: none;
			}
	 	 	#printPageButton2 {
	    	display: none;
			}}


			#loader {
			  position: absolute;
			  left: 50%;
			  top: 50%;
			  z-index: 1;
			  width: 150px;
			  height: 150px;
			  margin: -75px 0 0 -75px;
			  border: 16px solid #00AE9A;
			  border-radius: 50%;
			  border-top: 16px solid #000000;
			  width: 120px;
			  height: 120px;
			  -webkit-animation: spin 2s linear infinite;
			  animation: spin 2s linear infinite;
			}
			@-webkit-keyframes spin {
			  0% { -webkit-transform: rotate(0deg); }
			  100% { -webkit-transform: rotate(360deg); }
			}

			@keyframes spin {
			  0% { transform: rotate(0deg); }
			  100% { transform: rotate(360deg); }
			}

			/* Add animation to "page content" */
			.animate-bottom {
			  position: relative;
			  -webkit-animation-name: animatebottom;
			  -webkit-animation-duration: 1s;
			  animation-name: animatebottom;
			  animation-duration: 1s
			}
			@-webkit-keyframes animatebottom {
			  from { bottom:-100px; opacity:0 } 
			  to { bottom:0px; opacity:1 }
			}
			@keyframes animatebottom { 
			  from{ bottom:-100px; opacity:0 } 
			  to{ bottom:0; opacity:1 }
			}
			#myDiv {
			  display: none;
			  text-align: center;
			}
		</style>
		<?php
		if (!isset($_POST['username'])){header('Location: index.php');}
		$grouprows = 0;
		$username = test_input($_POST['username']);
		$password = test_input($_POST['password']);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_URL, test_input($_POST['url']));
		curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$resp = curl_exec($curl);
		$forms = json_decode($resp,true);
		if($errno = curl_errno($curl)) {
			$error_message = curl_strerror($errno);
			echo "Error: Could not connect to KoBo database. Please try again later.";
		}

		curl_close($curl);
		$item_id = $_POST['id'];
		$field = '_id';
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			function getRow($forms, $field, $item_id)
				{
				   foreach($forms as $key => $form)
				   {
				      if ( $form[$field] == $item_id )
				         return $key;
				   }
				   return false;
				}}
		$formId = getRow($forms, $field, $item_id);

		?>

	</head>
	<body style="font-family:arial; font-size: 13; width:80%; margin:auto;" onload="myFunction()">
		<div id="loader"></div>
		<div style="display:none;" id="myDiv" class="animate-bottom"></div>
		<script>
			var myVar;
			function myFunction() {
			    myVar = setTimeout(showPage, 1000);
			}
			function showPage() {
			  document.getElementById("loader").style.display = "none";
			  document.getElementById("myDiv").style.display = "block";
			}
		</script>
		<div align="center" style="width:100%">
			<img src="BPM_horizontal.png" alt="BPM Logo" style="max-width: 100%; height: auto;">
			</br></br>
		<div align="left">
		<form method="GET" action="index.php">
			<input type="hidden" name="action" value="Logged_out" ?>
			<input type="submit" id="printPageButton2" value='Log Out'></form>

		<form method="POST" action="index.php">
			<input type="hidden" name="url" value=<?php echo $_POST['url'] ?>>
			<input type="hidden" name="username" value=<?php echo $_POST['username'] ?>>
			<input type="hidden" name="password" value=<?php echo $_POST['password'] ?>>
			<input type="submit" id="printPageButton" value='Search'></form>
		</div>
		<div>
			<table style="width:100%; font-size: 13">
				<tr height="20px">
					<td align="left" colspan="6">
						</br><strong>
						Name: </strong> <?php if (isset($forms[$formId]['B/B01-FirstN'])){echo $forms[$formId]['B/B01-FirstN'] . " " . $forms[$formId]['B/B02-LastN'];}else echo "No Data";?>, <strong>Date of Report: </strong><?php echo strtoupper($forms[$formId]['M/M04-RepDate']);?>, <strong>Event Code:</strong> <?php if (isset($forms[$formId]['REF/RR04-IndvCode'])){echo strtoupper($forms[$formId]['REF/RR04-IndvCode']) . ", ";}else echo "No Data" . ", ";?> <strong>Transit Countries: </strong><?php if (isset($forms[$formId]['PI/TC'])){
								foreach($forms[$formId]['PI/TC'] as $trn){if(isset($trn['PI/TC/PI05-Route'])){echo strtoupper($trn['PI/TC/PI05-Route']) . "; ";}else echo "No data";}};
							?></br></br>
				</tr>
				<tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>METADATA</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>Reporting Country:</strong></br></br>
						<?php if (isset($forms[$formId]['M/M01-RepCountry'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['M/M01-RepCountry']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Reporting Organization:</strong></br></br>
						<?php if (isset($forms[$formId]['M/M02-RepOrg'])){echo strtoupper($forms[$formId]['M/M02-RepOrg']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Reporting Person:</strong></br></br>
						<?php if (isset($forms[$formId]['M/M03-RepIndv'])){echo strtoupper($forms[$formId]['M/M03-RepIndv']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Place of Interview:</strong></br></br>
						<?php if (isset($forms[$formId]['M/M05-PlaceInterview'])){echo str_replace("1","", str_replace("_", " ",str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['M/M05-PlaceInterview']))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Place - Details:</strong></br></br>
						<?php if (isset($forms[$formId]['M/M05-PlaceIntDetail'])){echo strtoupper($forms[$formId]['M/M05-PlaceIntDetail']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Form Code:</strong></br></br>
						<?php if (isset($forms[$formId]['REF/RR04-IndvCode'])){echo strtoupper($forms[$formId]['REF/RR04-IndvCode']);}else echo "No Data";?>
					</td>
					<td align="center">	
					</td>
				</tr>
				<tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>BIODATA</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>First Name:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B01-FirstN'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['B/B01-FirstN']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Last Name:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B02-LastN'])){echo strtoupper($forms[$formId]['B/B02-LastN']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Gender:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B03-Gender'])){echo strtoupper($forms[$formId]['B/B03-Gender']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Age:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B04-Age'])){echo strtoupper($forms[$formId]['B/B04-Age']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Country of Birth:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B06-CoB'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['B/B06-CoB']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Marital Status:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B05-Marital'])){echo strtoupper($forms[$formId]['B/B05-Marital']);}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px" id="musli">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>Claimed Nationality:</strong></br></br>
						<?php if (isset($forms[$formId]['B/BA08-Nationality'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['B/BA08-Nationality']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Place of Birth:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B07-PloBirth'])){echo strtoupper($forms[$formId]['B/B07-PloBirth']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Ethnicity:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B09-Ethnicity'])){echo strtoupper($forms[$formId]['B/B09-Ethnicity']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Religion:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B10-Religion'])){echo strtoupper($forms[$formId]['B/B10-Religion']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Spoken Languages:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B11-Language'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['B/B11-Language']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Current Location:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B13-Address'])){echo strtoupper($forms[$formId]['B/B13-Address']);}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>Contact:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B14-Contact'])){echo strtoupper($forms[$formId]['B/B14-Contact']);}else echo "No Data";?>
					</td>
					<td colspan="5" align="center" style="background-color:#F8F8F8;">
						<strong>Family Members Info:</strong></br></br>
						<?php if (isset($forms[$formId]['B/B12-Family'])){echo $forms[$formId]['B/B12-Family'];}else echo "No Data";;?>
					</td>
					<tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>PERSONAL INFORMATION</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>Govt. Issued ID:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI01-ID'])){echo strtoupper($forms[$formId]['PI/PI01-ID']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Type of Issued Doc.:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI04_IssuedDoc'])){echo strtoupper($forms[$formId]['PI/PI04_IssuedDoc']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Previously Recognized as a Refugee:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI02-Refugee'])){echo strtoupper($forms[$formId]['PI/PI02-Refugee']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Habitual Residence:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI03-CoResidence'])){echo strtoupper($forms[$formId]['PI/PI03-CoResidence']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>How Long Ago did you Leave your Country of Habitual Residence:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI04-LenghtDispl'])){echo str_replace("_", "-",strtoupper($forms[$formId]['PI/PI04-LenghtDispl']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Left with Family Members:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI06-Family'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['PI/PI06-Family']));}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong># of Women:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI061-FamWomen'])){echo strtoupper($forms[$formId]['PI/PI061-FamWomen']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong># of Men:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI062-FamMen'])){echo strtoupper($forms[$formId]['PI/PI062-FamMen']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong># of Girls:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI063-FamGirl'])){echo strtoupper($forms[$formId]['PI/PI063-FamGirl']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong># of Boys:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI064-FamBoy'])){echo strtoupper($forms[$formId]['PI/PI064-FamBoy']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Unvoluntarily Separated from all or some Family Members:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI07-FamSeparation'])){echo strtoupper($forms[$formId]['PI/PI07-FamSeparation']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>What Happened to them:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI08-FamSepHow'])){echo strtoupper($forms[$formId]['PI/PI08-FamSepHow']);}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr>	
					<td align="left" colspan="6" style="background-color:#F8F8F8;">
						<strong>Additional Comments:</strong></br></br>
						<?php if (isset($forms[$formId]['PI/PI09-Comments'])){echo $forms[$formId]['PI/PI09-Comments'];}else echo "No Data";?>
					</td>
				</tr>
				<?php
				if (($forms[$formId]['TQ2-Interception'] == 'yes') OR ($forms[$formId]['TQ3_Interception'] == 'yes')){
				?>
				</table>
				<?php//<p style="page-break-after: always;">&nbsp;</p>?>
				<table style="width:100%; font-size: 13">
				<tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>PROTECTION/SECURITY INCIDENT</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>Incident Date:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT01-Date'])){echo strtoupper($forms[$formId]['INT/INT01-Date']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Incident Time:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT02-When'])){echo strtoupper($forms[$formId]['INT/INT02-When']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Incident Country:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT03-CountryIn'])){echo strtoupper($forms[$formId]['INT/INT03-CountryIn']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Where did you try to Cross to:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT04-CountryTo'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['INT/INT04-CountryTo']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Who Intercepted/Apprehended you:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT05-Perpetrator'])){echo strtoupper($forms[$formId]['INT/INT05-Perpetrator']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Which Authority:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT05-PerpAuthority'])){echo str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>", strtoupper($forms[$formId]['INT/INT05-PerpAuthority']))));}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr>
					<td align="center">
						<strong># of Persons Involved:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT06-Victims'])){echo strtoupper($forms[$formId]['INT/INT06-Victims']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong># of Children Involved:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT07-VictimsChild'])){echo strtoupper($forms[$formId]['INT/INT07-VictimsChild']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Persons with Specific Needs in the group:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT07-VictimsPbs'])){echo str_replace(" ", ",</br>", strtoupper($forms[$formId]['INT/INT07-VictimsPbs']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong># of UASC:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT14_NumUASC'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['INT/INT14_NumUASC']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>While with the Authorities:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT08-AuthoritiesAction'])){echo str_replace("individual_int_1", "Interviewed", str_replace ("reason_fearing", "Asked about reason for flight", str_replace("entry_denial_r", "Informed why they cannot enter", str_replace("sign_document", "Asked to sign documents", str_replace("interpretation", "Had access to interpretation", str_replace("asylum", "Informed about asylum", str_replace(" ", ",</br>", $forms[$formId]['INT/INT08-AuthoritiesAction'])))))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Violence:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT09-Violence'])){echo str_replace("1","", str_replace("_", " ",strtoupper($forms[$formId]['INT/INT09-Violence'])));}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr>
					<td align="center">
						<strong>Other Violence:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT10-ViolenceWhat'])){echo str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>", strtoupper($forms[$formId]['INT/INT10-ViolenceWhat']))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Perpetrators:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT11-ViolenceWho'])){echo str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>", strtoupper($forms[$formId]['INT/INT11-ViolenceWho']))));}else echo "No Data";?>
					</td>
				<td align="left" colspan="4" style="background-color:#F8F8F8;">
						<strong>Comments:</strong></br></br>
						<?php if (isset($forms[$formId]['INT/INT12-Comments'])){echo $forms[$formId]['INT/INT12-Comments'];}else echo "No Data";?>
			</tr>
				<?php } ?>
			</table>
			<?php
				if ($forms[$formId]['TQ3-Pushback'] == 'yes'){
				?>
				</br></br>
				<table style="width:100%; font-size: 13">
				<tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>PUSHBACK</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>Pushback Date:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB01-Date'])){echo strtoupper($forms[$formId]['PB/PB01-Date']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Pushback Time:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB02-When'])){echo strtoupper($forms[$formId]['PB/PB02-When']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Pushback from:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB03-From'])){echo strtoupper($forms[$formId]['PB/PB03-From']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Pushback to:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB04-To'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['PB/PB04-To']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>From Which Country did you Enter:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB05-ArrivedFrom'])){echo strtoupper($forms[$formId]['PB/PB05-ArrivedFrom']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Who Returned you:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB06-Perpetrator'])){echo str_replace(" ", ",</br>", strtoupper($forms[$formId]['PB/PB06-Perpetrator']));}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr>
					<td align="center">
						<strong>Authority:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB07-PerpAuthority'])){echo str_replace("1","", str_replace("_", " ",strtoupper($forms[$formId]['PB/PB07-PerpAuthority'])));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Persons Involved:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB08-Victims'])){echo strtoupper($forms[$formId]['PB/PB08-Victims']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Children Involved:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB09-VictimsChild'])){echo str_replace(" ", ",</br>", strtoupper($forms[$formId]['PB/PB09-VictimsChild']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Persons with Specific Needs among Group:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB10-VictimsPbs'])){echo str_replace("MACEDONIA", "FYR MACEDONIA", strtoupper($forms[$formId]['PB/PB10-VictimsPbs']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong># of UASC:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB19_NumberUASC'])){echo str_replace(" ", ",</br>", strtoupper($forms[$formId]['PB/PB19_NumberUASC']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Volunt. Sent Back:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB11-Voluntary'])){echo strtoupper($forms[$formId]['PB/PB11-Voluntary']);}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr
				<tr>
					<td align="center">
						<strong>While with the Authorities:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB12-AuthoritiesAction'])){echo str_replace("individual_int_1", "Interviewed", str_replace ("reason_fearing", "Asked about reason for flight", str_replace("entry_denial_r", "Informed why they cannot enter", str_replace("sign_document", "Asked to sign documents", str_replace("interpretation", "Had access to interpretation", str_replace("asylum", "Informed about asylum", str_replace(" ", ",</br>", $forms[$formId]['PB/PB12-AuthoritiesAction'])))))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Expressed intention to seek asylum to the Auth.:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB13-Asylum'])){echo str_replace(" ", ",</br>", strtoupper($forms[$formId]['PB/PB13-Asylum']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Any Other Incident while you were Returned:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB14-Violence'])){echo str_replace(" ", ",</br>", strtoupper($forms[$formId]['PB/PB14-Violence']));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>What incident:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB15-ViolenceWhat'])){echo str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>",  strtoupper($forms[$formId]['PB/PB15-ViolenceWhat']))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Perpetrators:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB16-ViolenceWho'])){echo str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>", strtoupper($forms[$formId]['PB/PB16-ViolenceWho']))));}else echo "No Data";?>
					</td>
					<td align="center" colspan="4">
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr>
				<td align="left" colspan="6" style="background-color:#F8F8F8;">
						<strong>Comments:</strong></br></br>
						<?php if (isset($forms[$formId]['PB/PB17-Comments'])){echo $forms[$formId]['PB/PB17-Comments'];}else echo "No Data";?>
			</tr>
				<?php } ?>
			</table>
			<?php
				if ($forms[$formId]['TQ4-Smuggling'] == 'yes'){
				?>
				</br></br>
				<table style="width:100%; font-size: 13">
				<tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>SMUGGLING</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>Smuggling from:</strong></br></br>
						<?php if (isset($forms[$formId]['SM/SM01-From'])){echo strtoupper($forms[$formId]['SM/SM01-From']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Smuggling to:</strong></br></br>
						<?php if (isset($forms[$formId]['SM/SM02-To'])){echo strtoupper($forms[$formId]['SM/SM02-To']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Length of Stay with Smugglers (days):</strong></br></br>
						<?php if (isset($forms[$formId]['SM/SM03-Length'])){echo strtoupper($forms[$formId]['SM/SM03-Length']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Smuggling Fees:</strong></br></br>
						<?php if (isset($forms[$formId]['SM/SM04-SmugglingFees'])){echo strtoupper($forms[$formId]['SM/SM04-SmugglingFees']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Smuggling Currency:</strong></br></br>
						<?php if (isset($forms[$formId]['SM/SM05-Currency'])){echo strtoupper($forms[$formId]['SM/SM05-Currency']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Experienced Violence:</strong></br></br>
						<?php if (isset($forms[$formId]['SM/SM06-Violence'])){echo str_replace(" ", ",</br>", strtoupper($forms[$formId]['SM/SM06-Violence']));}else echo "No Data";?>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr>
					<td align="center">
						<strong>What kind of Violence:</strong></br></br>
						<?php if (isset($forms[$formId]['SM/SM07-ViolenceWhat'])){echo str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>",strtoupper($forms[$formId]['SM/SM07-ViolenceWhat']))));}else echo "No Data";?>
					</td>
					<td  colspan="5" align="left" style="background-color:#F8F8F8;">
						<strong>Smuggling Comments:</strong></br></br>
						<?php if (isset($forms[$formId]['SM/SM08-Comments'])){echo $forms[$formId]['SM/SM08-Comments'];}else echo "No Data";?>
					</td>
				</tr>
				<?php } ?>
			</table>
			<?php
				if ($forms[$formId]['TQ5-AsylumInfo'] == 'yes'){
				?>
				</br></br>
				<table style="width:100%; font-size: 13">
				<tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>ASYLUM INFORMATION</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
					<td align="center">
						<strong>Most Relevant Information Source:</strong></br></br>
						<?php if (isset($forms[$formId]['AI/AI01-FromWhom'])){echo str_replace("NGOS FAITH BAS", "NGOs", str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>",strtoupper($forms[$formId]['AI/AI01-FromWhom'])))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>How was the Information Provided:</strong></br></br>
						<?php if (isset($forms[$formId]['AI/AI02-How'])){echo str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>", strtoupper($forms[$formId]['AI/AI02-How']))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Was the informmation provided in a language that you can understand:</strong></br></br>
						<?php if (isset($forms[$formId]['AI/AI03-Language'])){echo strtoupper($forms[$formId]['AI/AI03-Language']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Was the Information Sufficient:</strong></br></br>
						<?php if (isset($forms[$formId]['AI/AI04-Quality'])){echo strtoupper($forms[$formId]['AI/AI04-Quality']);}else echo "No Data";?>
					</td>
					<td colspan="2" align="center">
				
					</td>

				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr>
					<td  colspan="6" align="left" style="background-color:#F8F8F8;">
						<strong>Comments:</strong></br></br>
						<?php if (isset($forms[$formId]['AI/AI05-Comments'])){echo $forms[$formId]['AI/AI05-Comments'];}else echo "No Data";?>
					</td>
				</tr>
				<?php } ?>
				</table>
				</br></br>
				<table style="width:100%; font-size: 13">
				<tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>ACCESS TO ASYLUM</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr height="20px">
						<td align="center">
						<strong>Did you recieve information on Asylum, prior to this interiview:</strong></br></br>
						<?php if (isset($forms[$formId]['AA/TQ5-AsylumInfo'])){echo str_replace("1","", str_replace("_", " ",str_replace(" ", ",</br>",strtoupper($forms[$formId]['AA/TQ5-AsylumInfo']))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Most Relevant reasons why you did not seek asylum so far:</strong></br></br>
						<?php if (isset($forms[$formId]['AA/AA01-NoAsylum_Why'])){echo str_replace("lenghty_proced_1", "lenghty procedure",
						str_replace("no_respect_hum", "lack of respect for human rights",
						str_replace("didnt_feel_wel_1", "did not feel welcome",
						str_replace("no_economic_op_1", "no economic opportunity",
						str_replace("no_education_o_1", "no educational opport.",
						str_replace("weak_welfare", "weak welfare system",
						str_replace("no_person_from_1", "no persons from my community",
						str_replace("family_friends_1", "family/friends reunification",
						str_replace("low_recognitio_1", "low recognition rate",
						str_replace("advised_not_to_1", "advised not to",
						str_replace("not_know_asylu_1", "not informed about asylum", str_replace(" ", ",</br>", $forms[$formId]['AA/AA01-NoAsylum_Why']))))))))))));}else echo "No Data";?>
					</td>
					<td colspan="4" align="left" style="background-color:#F8F8F8;">
						<strong>Reasons - Comments:</strong></br></br>
						<?php if (isset($forms[$formId]['AA/AA02-NoAs-Comments'])){echo $forms[$formId]['AA/AA02-NoAs-Comments'];}else echo "No Data";?>
					</td>
					</tr>
					<tr height="20px">
					<td colspan="6" height="20px">
					</td>
					</tr>
					<tr>
					<td align="center">
						<strong>Where did you apply for asylum:</strong></br></br>
						<?php if (isset($forms[$formId]['AA/AA03-YesAsylum_CoA'])){echo strtoupper($forms[$formId]['AA/AA03-YesAsylum_CoA']);}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>Why did you apply for asylum there:</strong></br></br>
						<?php if (isset($forms[$formId]['AA/AA04-YesAsylum_Why'])){echo str_replace("int_protection", "Int. Protection",
						str_replace("avoid_released_1", "to avoid detention",
						str_replace("to_be_accommod_1", "accommodation",
						str_replace("wait_to_contin_1", "to wait for an opp. to continue journey",
						str_replace("pushed_by_auth_1", "pushed by authorities to apply",
						str_replace("regularize_sta_1", "to regulize status",
						str_replace("felt_welcome", "felt welcome",
						str_replace("economic_oppor", "Economic opportunities",
						str_replace("education_oppo_1", "Education opportunities",
						str_replace("welfare_system", "good welfare",
						str_replace("persons_from_c_1", "pers. from community are here",
						str_replace("family_friends_1", "family/friends are here",
						str_replace("high_recogniti_1", "high recognition rate",
						str_replace("FR_dublin", "access to reunion under dublin",
						str_replace("return_dublin", "return under dublin",
						str_replace("onlyopportunit", "only opportunity to seek asylum", str_replace(" ", ",</br>", $forms[$formId]['AA/AA04-YesAsylum_Why'])))))))))))))))));}else echo "No Data";?>
					</td>
					<td align="center">
						<strong>If you applied, why did you decide to leave:</strong></br></br>
						<?php if (isset($forms[$formId]['AA/AA05-YesAsylum_WhyLeft'])){echo str_replace("wait_to_contin", "Wanted to continue to another country",
							str_replace("to_reunify_fam", "to Unite with family/friends",
							str_replace("asylum_procedu", "complex asylum procedure",
							str_replace("lack_of_adequa", "lack of integration services",
							str_replace("didnot_feel_we", "did not feel welcome",
							str_replace("dublin_return", "dublin return",
							str_replace("rec_condition", "reception conditions",
							str_replace(" ", ",</br>",$forms[$formId]['AA/AA05-YesAsylum_WhyLeft']))))))));}else echo "No Data";?>
					</td>
					<td colspan="3" align="center">
					
					</td>

				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr>
				<tr>
					<td  colspan="6" align="left" style="background-color:#F8F8F8;">
						<strong>General Comments:</strong></br></br>
						<?php if (isset($forms[$formId]['AA/General_comments'])){echo $forms[$formId]['AA/General_comments'];}else echo "No Data";?>
					</td>
				</tr>
					</table>
					<?php if (isset($forms[$formId]['REF/PHOTO_1'])){?>
					<p style="page-break-after: always;">&nbsp;</p>
					<table style="width:100%; font-size: 13">
					<tr>
					<td colspan="6", bgcolor="#00AE9A", align="center" style="color: white; font-size: 15">
						<strong>PHOTOS</strong>
					</td>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
					</tr>
				<tr>
						<?php

						if (isset($forms[$formId]['_attachments'])){
								foreach($forms[$formId]['_attachments'] as $pic){$image = "https://kobocat.unhcr.org/" . $pic['download_url'];
								?>
								<td colspan="3" align="center">
								<img width="90%" src=<?php echo $image;?> alt="Photo">
								</td>
								<?php
						}};
						?>
				</tr>
				<tr height="20px">
					<td colspan="6" height="20px">
					</td>
				</tr><?php } ?>
			</table>
		</div>
	</body>
</html>