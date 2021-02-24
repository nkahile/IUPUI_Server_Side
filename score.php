<?php	include "judge_check.php" ?>

<title>Score</title>
<div class="wrapper">

<div class="main-f">
	<h1><strong>Judge Score Entry</strong></h1>
	<div class="form-s">
		<form action="Judge.php?action=score" method="post">

			<div class="label">
				<label for="score">Project </label>
				<input type="text"><br>
			</div>

			<label>Number: </label>
			<input type = "number" step="any" min = "0" max = "100" /><br>
			<button type="submit" class="btn">Submit</button>

		</form>

	<form action="Judge.php" method="post">
		<button type="submit">Back</button>
	</form>

	</div>
</div>
</div>
