const $=jQuery;
let soseResponse;
let soseChart;

async function soseRenderChart() {
	if (soseChart)
		soseChart.destroy();

	$("#soseChartSelect").attr('disabled',true);
	$("#soseChartPrev").attr('disabled',true);
	$("#soseChartNext").attr('disabled',true);
	$('#spanLabel').html("");

	let timestamp=soseChartTimestamp;
	soseResponse=await $.ajax(soseAjaxUrl,{
		data: {
			action: "sose_chart_data",
			timestamp: timestamp,
			scope: $("#soseChartSelect").val(),
			var: soseVar
		},
		dataType: "json"
	});

	$("#soseChartSelect").attr('disabled',false);
	$("#soseChartPrev").attr('disabled',false);
	$("#soseChartNext").attr('disabled',false);
	$('#spanLabel').html(soseResponse.rangeLabel);

	var ctx = document.getElementById('soseChart').getContext('2d');
	soseChart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'line',

	    // The data for our dataset
	    data: {
	        labels: soseResponse.labels,
	        datasets: [{
	            borderColor: 'rgb(255, 99, 132)',
	            data: soseResponse.tempdata,
	            label: soseVar.charAt(0).toUpperCase()+soseVar.slice(1)
	        }]
	    },

	    // Configuration options go here
	    options: {
	    	animation: {
	    		duration: 0
	    	},
	        /*legend: {
	        	display: false
	        },*/
	        scales: {
	        	xAxes: [{
	        		ticks: {
	        			maxTicksLimit: 12
	        		}
	        	}]
	        }
    	},
	});
}

function soseInitChart() {
	$("#soseChartSelect").change(soseRenderChart);

	$("#soseChartPrev").click(()=>{
		soseChartTimestamp=soseResponse.prevTimestamp;
		soseRenderChart();
	});

	$("#soseChartNext").click(()=>{
		soseChartTimestamp=soseResponse.nextTimestamp;
		soseRenderChart();
	});

	soseRenderChart();
}

if (document.getElementById("soseChart"))
	soseInitChart();
