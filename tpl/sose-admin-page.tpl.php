<div class="wrap">
	<h1>Somaseeds</h1>

	<?php if ($apiResult) { ?>
		<div class="notice notice-success is-dismissible"><p>
			<?php echo esc_html($apiResult); ?>
		</p></div>
	<?php } ?>

	<?php if ($apiError) { ?>
		<div class="notice notice-error is-dismissible"><p>
			<?php echo esc_html($apiError); ?>
		</p></div>
	<?php } ?>

	<?php if ($statusError) { ?>
		<div class="notice notice-error"><p>
			<?php echo esc_html($statusError); ?>
		</p></div>
	<?php } else { ?>
		<div class="card">
			<h2 class="title">Live Values</h2>
			<p>Temperature: <?php printf("%.2f",($vars["temperature"])); ?></p>
			<p>Humidity: <?php printf("%.2f",$vars["humidity"]); ?></p>
			<p>pH: <?php printf("%.2f",$vars["ph"]); ?></p>
			<p>pH Raw Reading: <?php echo esc_html($vars["phRaw"]); ?></p>
		</div>

		<h2>Light Timer</h2>
		<form action="<?php echo $formurl; ?>" method="POST">
			<input type="hidden" name="page" value="somaseeds"/>
			<table class="form-table">
				<tr>
					<th scope="row">Schedule</th>
					<td>
						<input type="text" name="lightSchedule" value="<?php echo esc_attr($lightSchedule); ?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row">Duration</th>
					<td>
						<input type="text" name="lightDuration" value="<?php echo esc_attr($lightDuration); ?>"/>
					</td>
				</tr>
			</table>
			<input type="submit" value="Update Light Settings" class="button button-primary" name="light"/>
		</form>

		<h2>Pump Motor</h2>
		<form action="<?php echo $formurl; ?>" method="POST">
			<input type="hidden" name="page" value="somaseeds"/>
			<table class="form-table">
				<tr>
					<th scope="row">Forward Schedule</th>
					<td>
						<input type="text" name="forwardSchedule" value="<?php echo esc_attr($forwardSchedule); ?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row">Forward Duration</th>
					<td>
						<input type="text" name="forwardDuration" value="<?php echo esc_attr($forwardDuration); ?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row">Backward Schedule</th>
					<td>
						<input type="text" name="backwardSchedule" value="<?php echo esc_attr($backwardSchedule); ?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row">Backward Duration</th>
					<td>
						<input type="text" name="backwardDuration" value="<?php echo esc_attr($backwardDuration); ?>"/>
					</td>
				</tr>
			</table>
			<input type="submit" value="Update Motor Settings" class="button button-primary" name="motor"/>
		</form>

		<h2>phCalibration</h2>
		<form action="<?php echo $formurl; ?>" method="POST">
			<input type="hidden" name="page" value="somaseeds"/>
			<table class="form-table">
				<tr>
					<th scope="row">The raw value...</th>
					<td>
						<input type="text" name="phFirstRaw" value="<?php echo esc_attr($phFirstRaw); ?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row">...translates to</th>
					<td>
						<input type="text" name="phFirstTranslated" value="<?php echo esc_attr($phFirstTranslated); ?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row">The raw value...</th>
					<td>
						<input type="text" name="phSecondRaw" value="<?php echo esc_attr($phSecondRaw); ?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row">...translates to</th>
					<td>
						<input type="text" name="phSecondTranslated" value="<?php echo esc_attr($phSecondTranslated); ?>"/>
					</td>
				</tr>
			</table>
			<input type="submit" value="Update pH Calibration" class="button button-primary" name="ph"/>
		</form>

		<h2>Timer Syntax</h2>
		<p>
			The schedule uses the <a href="https://github.com/breejs/later">later.js</a> library.
			It accepts expressions like:
			<ul>
				<li>every 1 hour</li>
				<li>on the 5 minute on the 2,3,4 hour every weekday</li>
				<li>on the 0,15,30,45 second<li>
			</ul>
			The duration uses the <a href="https://www.npmjs.com/package/ms">ms</a> library.
			It accepts expressions like:
			<ul>
				<li>1 hour</li>
				<li>30 min</li>
				<li>5 sec<li>
			</ul>
		</p>

		<p>The below is for testing...</p>

		<h2>Relays</h2>
		<?php for ($i=0; $i<4; $i++) { ?>
			<p>
				Relay <?php echo $i; ?>
				<a class="button"
						href="<?php echo admin_url("admin.php?page=somaseeds&relay=$i&val=1"); ?>">
					On
				</a>
				<a class="button"
						href="<?php echo admin_url("admin.php?page=somaseeds&relay=$i&val=0"); ?>">
					Off
				</a>
			</p>
		<?php } ?>

		<h2>Motor</h2>
		<form action="<?php echo $formurl; ?>" method="GET">
			<input type="hidden" name="page" value="somaseeds"/>
			<input type="submit" class="button" value="Reverse" name="reverse"/>
			<input type="submit" class="button" value="Stop" name="stop"/>
			<input type="submit" class="button" value="Start" name="start"/>
		</form>
	<?php } ?>
</div>