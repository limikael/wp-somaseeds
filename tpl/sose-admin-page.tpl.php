<div class="wrap">
	<h1>Somaseeds Testing</h1>

	<p>This is just for testing, we will not control things like this later obviously... :)</p>

	<h2>Relays</h2>

	<?php
		$t=time();
		$m=(floor($t/86400)*86400);

		echo $t."<br/>";
		echo $m."<br/>";

		echo date("Y-m-d H:i:s",$t)."<br/>";
		echo date("Y-m-d H:i:s",$m)."<br/>";
//		echo get_date_from_gmt(date("Y-m-d H:m:s",time()))."<br/>";
	?>

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

	<h2>Stepper Start and Stop</h2>

	<form action="<?php echo $formurl; ?>" method="GET">
		<input type="hidden" name="page" value="somaseeds"/>
		<table class="form-table">
			<tr>
				<th><label>Rpm</label></th>
				<td><input type="text" name="rpm"></td>
			</tr>
			<tr>
				<th><label></label></th>
				<td>
					<input type="submit" class="button" value="Start" name="start"/>
					<input type="submit" class="button" value="Stop" name="stop"/>
				</td>
			</tr>
		</table>
	</form>

	<h2>Stepper Revolutions</h2>

	<p>
		<a class="button"
				href="<?php echo admin_url("admin.php?page=somaseeds&steps=4000"); ?>">
			-20
		</a>
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
		<a class="button"
				href="<?php echo admin_url("admin.php?page=somaseeds&steps=4000"); ?>">
			+20
		</a>
	</p>
</div>