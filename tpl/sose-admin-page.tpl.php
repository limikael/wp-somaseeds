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
		<h2>Light Timer</h2>
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