<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">

  <title>Saúde</title>
  <meta name="description" content="Example Health Admin">
  <meta name="Max Shapiro" content="Example Health">
  <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans" rel="stylesheet">
  <link rel="stylesheet" href="style/styles.css?v=1.0">
  <link rel="stylesheet" href="style/admin.css?v=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">

  <link rel="icon" type="image/png" href="images/fictional.svg">

  <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js'></script>

</head>

<body>
  <div class="container">
    <div class="banner">
      <div class="control">
        <div class="brand">
          <img class="logo" src="/images/fictional.svg" alt="example health logo">
          <div class="Fictional">Saúde</div>
        </div>
        <menu class="menu">
          <menuitem class="account">Login</menuitem>
        </menu>
      </div>
    </div>

    <div class="group">
      <div class="manage">
		<?php require "dataHandler.php";

			$patients = getPatients();
			$totalPatients = count($patients);
			$diseases = getDiseases();
			$prescriptions = getPrescriptions();

			$age = [0,0,0,0];
			$males = 0;
			$females = 0;

			for($patient = 0; $patient < $totalPatients; $patient++) {

				$patientAge = getAge($patients[$patient]["CA_DOB"]);
				if ($patientAge < 18) {
					$age[0] += 1;
				} elseif ($patientAge < 36) {
					$age[1] += 1;
				} elseif ($patientAge < 55) {
					$age[2] += 1;
				} else {
					$age[3] += 1;
				}

				if ($patients[$patient]["CA_GENDER"] == "M") {
					$males += 1;
				} else {
					$females += 1;
				}

			} 

			for($ageRange = 0; $ageRange < count($age); $ageRange++) {
				$age[$ageRange] = round(($age[$ageRange] * 100.0)/$totalPatients,2);
			}

			echo '<div class="hospital"></div>
			        <div class="summary">
			          <div class="numbers">

			            <div class="totalblock">
			              <div class="totallabel">Total Pacientes
			              </div>
			              <div class="totalcount">' . $totalPatients . '</div>
			            </div>

			            <div class="numberblock">
			              <div class="numberlabel">0 - 17 anos: ' . $age[0] . '%</div>
			              <div class="percentage">
			                <div class="percentageused" style="width:' . $age[0] . '%;"></div>
			                <div class="percentagefree" style="width:' . (100 - $age[0]) . '%;"></div>
			              </div>
			            </div>

			            <div class="numberblock">
			              <div class="numberlabel">18 - 35 anos: ' . $age[1] . '%</div>
			              <div class="percentage">
			                <div class="percentageused" style="width:' . $age[1] . '%;"></div>
			                <div class="percentagefree" style="width:' . (100 - $age[1]) . '%;"></div>
			              </div>
			            </div>

			            <div class="numberblock">
			              <div class="numberlabel">36 - 54 anos: ' . $age[2] . '%</div>
			              <div class="percentage">
			                <div class="percentageused" style="width:' . $age[2] . '%;"></div>
			                <div class="percentagefree" style="width:' . (100 - $age[2]) . '%;"></div>
			              </div>
			            </div>

			            <div class="numberblock">
			              <div class="numberlabel">55+ anos: ' . $age[3] . '%</div>
			              <div class="percentage">
			                <div class="percentageused" style="width:' . $age[3] . '%;"></div>
			                <div class="percentagefree" style="width:' . (100 - $age[3]) . '%;"></div>
			              </div>
			            </div>

			            <div class="totalblock">
			              <div class="totallabel">Diabetes
			              </div>
			              <div class="totalcount">' . round(($diseases["DIABETES"] * 100.0)/$totalPatients,2) . '%</div>
			            </div>

			            <div class="totalblock">
			              <div class="totallabel">Asma
			              </div>
			              <div class="totalcount">' . round(($diseases["ASTHMA"] * 100.0)/$totalPatients,2) . '%</div>
			            </div>

			          </div>
			          <div class="charts">
			            <canvas class="canvasbuffer" id="genderChart" width="180" height="180"></canvas>
			            <canvas class="canvasbuffer" id="medicineChart" width="180" height="180"></canvas>
			          </div>
			        </div>
			        <div class="patients"></div>
			        <div class="box boxmod">
			          <div class="boxheader">
			            <div class="boxlabel">List de Pacientes</div>
			            <div class="boxlabel">' . $totalPatients . '</div>
			          </div>
			          <div class="listbox">';

			for($x = 0; $x < $totalPatients; $x++) {
				$gender = "";

				if ($patients[$x]["CA_GENDER"] == "M")
					$gender = "Homem";
				else
					$gender = "Mulher";

				echo '<div class="boxitem"><img class="beaker" src="images/patient.svg">
              			<div class="boxitemlabel">' . $patients[$x]["CA_FIRST_NAME"] . ' ' . $patients[$x]["CA_LAST_NAME"] . ' - ' . $gender . ' - Idade ' . getAge($patients[$x]["CA_DOB"]) .'</div>
           						</div>';
			}
			echo '</div></div></div></div></div></body>
				<script>
					var ctx = document.getElementById("genderChart").getContext("2d");

					data = {
						datasets: [{
					   		data: ['.$males.', '.$females.'],
					  		backgroundColor: ["#ff6494", "#ffa18b", "#ffd0c5"]
					    }],
					    labels: [
					   		"Homem",
					   		"Mulher"
					    ]
					};

					var myDoughnutChart = new Chart(ctx, {
						type: "doughnut",
					    data: data
					});

					var ctx = document.getElementById("medicineChart").getContext("2d");

					data = {
					 	datasets: [{
					   		data: [' . (int)$prescriptions[0]["TOTAL_PATIENTS"] . ', ' . (int)$prescriptions[1]["TOTAL_PATIENTS"] . ', ' . (int)$prescriptions[2]["TOTAL_PATIENTS"] . '],
					     	backgroundColor: ["#ff6494", "#ffa18b", "#ffd0c5"]
					    }],
					    labels: [
					    	"' . $prescriptions[0]["DRUG_NAME"] . '",
					      	"' . $prescriptions[1]["DRUG_NAME"] . '",
					      	"' . $prescriptions[2]["DRUG_NAME"] . '"
					    ]
					};

					var myDoughnutChart = new Chart(ctx, {
					  	type: "doughnut",
					    data: data
					});
				</script>';
		?>
</html>
