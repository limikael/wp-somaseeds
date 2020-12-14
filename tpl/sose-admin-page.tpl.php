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

		<h2>Control</h2>
		<form action="<?php echo $formurl; ?>" method="POST" id="soseAdminForm">
			<input type="hidden" name="page" value="somaseeds"/>

			<table class="form-table">
				<tr>
					<th scope="row">Mode</th>
					<td>
						<select id="sosemode" name="mode">
							<?php display_select_options(array(
								"manual"=>"Manual",
								"auto"=>"Automatic",
								"autodebug"=>"Automatic with debug"
							),$vars["mode"]); ?>
						</select>
					</td>
				</tr>
			</table>

				<h2>phCalibration</h2>
				<input type="hidden" name="page" value="somaseeds"/>
				<table class="form-table">
					<tr>
						<th scope="row">The raw value...</th>
						<td>
							<input type="text"
									name="phFirstRaw"
									value="<?php echo esc_attr($phFirstRaw); ?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">...translates to</th>
						<td>
							<input type="text"
									name="phFirstTranslated"
									value="<?php echo esc_attr($phFirstTranslated); ?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">The raw value...</th>
						<td>
							<input type="text"
									name="phSecondRaw"
									value="<?php echo esc_attr($phSecondRaw); ?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">...translates to</th>
						<td>
							<input type="text"
									name="phSecondTranslated"
									value="<?php echo esc_attr($phSecondTranslated); ?>"/>
						</td>
					</tr>
				</table>

			<div id="soseAutoControl">
				<h2>Light Timer</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Schedule</th>
						<td>
							<input type="text"
									name="lightSchedule"
									value="<?php echo esc_attr($lightSchedule); ?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">Duration</th>
						<td>
							<input type="text"
									name="lightDuration"
									value="<?php echo esc_attr($lightDuration); ?>"/>
						</td>
					</tr>
				</table>

				<h2>Pump Motor</h2>
				<input type="hidden" name="page" value="somaseeds"/>
				<table class="form-table">
					<tr>
						<th scope="row">Forward Schedule</th>
						<td>
							<input type="text"
									name="forwardSchedule"
									value="<?php echo esc_attr($forwardSchedule); ?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">Forward Duration</th>
						<td>
							<input type="text"
									name="forwardDuration"
									value="<?php echo esc_attr($forwardDuration); ?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">Backward Schedule</th>
						<td>
							<input type="text"
									name="backwardSchedule"
									value="<?php echo esc_attr($backwardSchedule); ?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">Backward Duration</th>
						<td>
							<input type="text"
									name="backwardDuration"
									value="<?php echo esc_attr($backwardDuration); ?>"/>
						</td>
					</tr>
				</table>

				<h2>Temperature</h2>
				<input type="hidden" name="page" value="somaseeds"/>
				<table class="form-table">
					<tr>
						<th scope="row">Low Value</th>
						<td>
							<input type="text"
									name="lowTemp"
									value="<?php echo esc_attr($lowTemp); ?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">High Value</th>
						<td>
							<input type="text"
									name="highTemp"
									value="<?php echo esc_attr($highTemp); ?>"/>
						</td>
					</tr>
				</table>

				<div id="soseDebugControl">
					<table class="form-table">
						<tr>
							<th scope="row">Debug Temp</th>
							<td>
									<input type="text"
											class="small-text"
											value="<?php echo esc_attr($debugTemp); ?>"
											name="debugTemp"/>
							</td>
						</tr>
					</table>
				</div>

				<div class="card">
					<h2>Timer Syntax</h2>
					<p>
						The schedule uses the <a href="https://github.com/breejs/later">later.js</a> library.
						It accepts expressions like:
						<ul>
							<li>every 1 hour</li>
							<li>on the 5 minute on the 2,3,4 hour every weekday</li>
							<li>on the 0,15,30,45 second</li>
						</ul>
						The duration uses the <a href="https://www.npmjs.com/package/ms">ms</a> library.
						It accepts expressions like:
						<ul>
							<li>1 hour</li>
							<li>30 min</li>
							<li>5 sec</li>
						</ul>
					</p>
				</div>
			</div>

			<div id="soseManualControl">
				<h2>Manual Control</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Light</th>
						<td>
							<select name="light">
								<?php display_select_options(array(
									0=>"Off",
									1=>"On"
								),$vars["light"]);?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">Heater</th>
						<td>
							<select name="heater">
								<?php display_select_options(array(
									0=>"Off",
									1=>"On"
								),$vars["heater"]);?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">Pump</th>
						<td>
							<select name="pump">
								<?php display_select_options(array(
									-1=>"-1",
									0=>"0",
									1=>"1"
								),$vars["pump"]);?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">Fan</th>
						<td>
							<select name="fan">
								<?php display_select_options(array(
									0=>"0",
									1=>"1"
								),$vars["fan"]);?>
							</select>
						</td>
					</tr>
				</table>
			</div>

			<br/>
			<input type="submit" value="Save"
					class="button button-primary" name="updateSettings"/>
		</form>
	<?php } ?>
</div>