function soseRenderChart() {
	console.log("rendering chart");

	console.log(soseAjaxUrl);

	var ctx = document.getElementById('soseChart').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'line',

	    // The data for our dataset
	    data: {
	        labels: ['January', '', 'February', 'March', 'April', 'May', 'June', 'July'],
	        datasets: [{
	            //label: 'My First dataset',
	            /*backgroundColor: 'rgb(255, 99, 132)',*/
	            borderColor: 'rgb(255, 99, 132)',
	            data: [0, 10, 5, 2, 20, 30, 100, 60]
	        }/*,{
	            label: 'My Second dataset',
	            backgroundColor: 'rgb(255, 99, 132)',
	            borderColor: 'rgb(255, 99, 132)',
	            data: [200, 10, 5, 2, 20, 30, 100, 60]
	        }*/]
	    },

	    // Configuration options go here
	    options: {
	        legend: {
	        	display: false
	        }
    	},
	});
}

if (document.getElementById("soseChart"))
	soseRenderChart();
