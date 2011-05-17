<html>
	<head>
		<title>Checklist</title>
	</head>
	<body>
<?php
	function userDoneProblem($u, $p) {
		return true;
	}

	//Change the following arrays
	$user = array(20748);
	$problems = array(2006,2007);

	echo '<table border="1">
		<tr>
			<td></td>';

			for ($i=0; $i<count($user); $i++)
				echo '<td>'.$user[$i].'</tr>';
		echo '</tr>';

		for ($i=0; $i<count($problems); $i++) {
			$urlProb=$problems[$i]-1999;

			echo '<tr>
				<td>
					<a href="http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&category=3&page=show_problem&problem='.$urlProb.'">'.$problems[$i].'</a>
				</td>';

			for ($j=0; $j<count($user); $j++) {
				echo '<td';
					if (userDoneProblem($user[$j],$problems[$i])) echo ' style="background-color: green;" ';
					echo '>
				</td>';
			}
			echo '</tr>';
		}
	echo '</table>';
?>
	</body>
</html>
