<?php
	include "laHandler.php";

	$problemsFile = "config/problems.json";
	$otherFiles = array("b" => "config/brasileira.json",
	                    "m" => "config/mundial.json",
			   );

        if(isset($_GET["p"]) && isset($otherFiles[$_GET["p"]])) {
	  $problemsFile = $otherFiles[$_GET["p"]];
        }

	$users = json_decode(file_get_contents("config/users.json"), true);
	$problems = json_decode(file_get_contents($problemsFile), true);

	// $problems = array_map(function($p) { $p['name'] = $p['id']; return $p; }, $problems); // Works only with PHP version >= 5.3
	foreach($problems as $k => $problem) {
		$problems[$k]['name'] = $problem['id'] . ' - ' . $problem['name'];
	}

	$live = new laHandler;

  	// $ut = $live->getSolvedProblemsForUsers(array_map(function($u){return $u['id'];}, $users)); // Works only with PHP version >= 5.3
	$ut = array();
	foreach($users as $user) {
		$ut[] = $user['id'];
	}
  	$ut = $live->getSolvedProblemsForUsers($ut);

	$lt = array();
	foreach($users as $user) {
		foreach($problems as $problem) {
			$lt[$user['id']][$problem['id']] = array_key_exists($problem['id'], $ut[$user['id']]);
		}
	}
?>

<html>
	<head>
		<title>Checklist</title>
	</head>

	<body>
		<table border='1'>
			<tr>
				<td colspan='2'>Checklist</td>

				<?php foreach($users as $k => $user) { ?>
					<?php $users[$k]['solved'] = 0; ?>
					<td>
						<a href='<?=$live->getUserURL($user['id'])?>'><?=$user['name']?></a>
					</td>
				<?php } ?>

				<td>Solvers</td>
			</tr>

			<?php foreach($problems as $problem) { ?>
				<?php $solvers = 0 ?>
				<tr>
					<?php
						/*rowspan = contestTable[problem.contestName]
						if rowspan ~= 0 then
							contestTable[problem.contestName] = 0*/
					?>
					<!--
					<td align='center' rowspan='<?=1/*rowspan*/?>'>
						<?="nome_do_contest"/*string.gsub(problem.contestName, '-', '<br/>')*/?>
					</td>
					-->
					<?php
						/*end*/
					?>

					<td colspan='2'>
						<a href="<?=$live->getProblemURL($problem['id'])?>"><?=$problem['judge'].": ".$problem['name']?></a>
					</td>
					<?php foreach($users as $k => $user) { ?>
						<?php
							$color = '#FFFFFF';
							$text = "&nbsp;";
							if ($lt[$user['id']][$problem['id']]) {
								$solvers++;
								$users[$k]['solved']++;
								$color =  "#55FF55";
								//$text = "AC";
							}
							/*
							if lt[user.id][problem.id] == 'NY' then
								color = '#FFFFFF'
								text = '&nbsp;'
							elseif lt[user.id][problem.id] == 'AC' then
								solvers = solvers + 1
								user.solved = user.solved + 1
								color = '#55FF55'
								text = '&nbsp;'
							else
								color = '#FF9090'
								text = lt[user.id][problem.id]
							end*/
						?>
						<td bgcolor=<?=$color?> align='center'>
							<?=$text?>
						</td>
					<?php } ?>
					<td><?=$solvers?></td>
				</tr>
			<?php } ?>
			<tr>
				<td colspan='2'>Solved:</td>
				<?php foreach($users as $user) { ?>
					<td><?=$user['solved']?></td>
				<?php } ?>
				<td>---</td>
			</tr>
		</table>
	</body>
</html>
