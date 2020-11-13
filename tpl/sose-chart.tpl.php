<p>
	<button disabled id="soseChartPrev">&lt;&lt; Prev</button>
	<button disabled id="soseChartNext">Next &gt;&gt;</button>
	<select id="soseChartSelect" disabled>
		<option value="hour">Hour</option>
		<option value="day">Day</option>
		<option value="month">Month</option>
	</select>
</p>
<div>
	<span>&nbsp;</span>
	<b><span id="spanLabel"></span></b>
</div>
<div id="chartContainer">
	<canvas id="soseChart" width="100" height="50"></canvas>
</div>
<script>
	var soseAjaxUrl="<?php echo esc_js(admin_url('admin-ajax.php')); ?>";
	var soseChartTimestamp=<?php echo $timestamp; ?>;
	var soseVar="<?php echo $var; ?>";
</script>
