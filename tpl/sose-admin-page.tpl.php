<div class="wrap">
	<h1>Somaseeds Testing</h1>

	<p>This is just for testing, we will not control things like this later obviously... :)</p>

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
</div>