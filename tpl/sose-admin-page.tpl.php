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

	<h2>Stepper</h2>

	<p>
		<a class="button"
				href="<?php echo admin_url("admin.php?page=somaseeds&steps=-1000"); ?>">
			-5
		</a>
		<a class="button"
				href="<?php echo admin_url("admin.php?page=somaseeds&steps=-200"); ?>">
			-2
		</a>
		<a class="button"
				href="<?php echo admin_url("admin.php?page=somaseeds&steps=-200"); ?>">
			-1
		</a>
		<a class="button"
				href="<?php echo admin_url("admin.php?page=somaseeds&steps=200"); ?>">
			+1
		</a>
		<a class="button"
				href="<?php echo admin_url("admin.php?page=somaseeds&steps=400"); ?>">
			+2
		</a>
		<a class="button"
				href="<?php echo admin_url("admin.php?page=somaseeds&steps=1000"); ?>">
			+5
		</a>
	</p>
</div>