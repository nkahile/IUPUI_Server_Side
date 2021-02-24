<?php include "header.php"; ?>

<title>New Judge</title>
<div class="wrapper">

<div class="wrapper">
<div class="main-f">
	<h1><strong>New Judge Registration</strong></h1>
	<div class="form-s">
		<form action="Judge.php">

			<!-- First Name -->
			<div class="label">
				<label for="judge_first_name">First Name </label>
				<input type="text"><br>
			</div>

			<!-- Middle Name -->
			<div class="label">
				<label for="judge_middle_name">Middle Name </label>
				<input type="text"><br>
			</div>

			<!-- Last Name -->
			<div class="label">
				<label for="judge_last_name">Last Name </label>
				<input type="text"><br>
			</div>

			<!-- Title -->
			<div class="label">
				<label for="title">Preferred Title </label>
				<input type="text"><br>
			</div>

			<!-- Degree -->
			<label for="degree">Highest Degree Earned </label>
			<select name="degree" id="degree">
				<option value=1>Degree 1</option>
				<option value=1>Degree 2</option>
				<option value=1>Degree 3</option>
			</select><br>

			<!-- Employer -->
			<div class="label">
				<label for="employer">Current Employer </label>
				<input type="text"><br>
			</div>

			<!-- Email -->
			<div class="label">
				<label for="email">Email </label>
				<input type="text"><br>
			</div>

			<!-- Username -->
			<div class="label">
				<label for="username">Username </label>
				<input type="text"><br>
			</div>

			<!-- Password -->
			<div class="label">
				<label for="password">Password </label>
				<input type="text"><br>
			</div>

			<!-- CatPref1 -->
			<label for="catpref1">Primary Category Preference </label>
			<select name="catpref1" id="catpref1">
				<option value=1>Category 1</option>
				<option value=2>Category 2</option>
				<option value=3>Category 3</option>
			</select><br>

			<!-- CatPref2 -->
			<label for="catpref2">Secondary Category Preference </label>
			<select name="catpref2" id="catpref2">
				<option value=1>Category 1</option>
				<option value=2>Category 2</option>
				<option value=3>Category 3</option>
			</select><br>

			<!-- CatPref3 -->
			<label for="catpref3">Tertiary Category Preference </label>
			<select name="catpref3" id="catpref3">
				<option value=1>Category 1</option>
				<option value=2>Category 2</option>
				<option value=3>Category 3</option>
			</select><br>

			<!-- LowerGradePref -->
			<label for="lgp">Lowest grade level you would prefer to judge </label>
			<select name="lgp" id="lgp">
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
				<option value=6>6</option>
				<option value=7>7</option>
				<option value=8>8</option>
				<option value=9>9</option>
				<option value=10>10</option>
				<option value=11>11</option>
				<option value=12>12</option>
			</select><br>

			<!-- UpperGradePref -->
			<label for="hgp">Highest grade level you would prefer to judge </label>
			<select name="hgp" id="lgp">
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
				<option value=6>6</option>
				<option value=7>7</option>
				<option value=8>8</option>
				<option value=9>9</option>
				<option value=10>10</option>
				<option value=11>11</option>
				<option value=12>12</option>
			</select><br>

			<button type="submit">Submit<button>

		</form>
	</div>
            
</div>
</div>
</div>

<?php include "footer.php" ?>
