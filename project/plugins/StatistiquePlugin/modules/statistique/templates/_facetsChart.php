<script type="text/javascript">
$(function () {
        $('#chart_container').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25,
                width: 600
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ["<?php echo implode('", "', $chartConfig['categories']->getRawValue()) ?>"]
            },
            yAxis: {
                title: {
                    text: 'Saisies DRM'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [
            <?php foreach ($chartConfig['series'] as $key => $value): ?>
            {
				name: "<?php echo $key ?>",
			    data: [<?php echo implode(', ', $value->getRawValue()) ?>]
            },
            <?php endforeach; ?>
            ]
        });
    });
</script>
<div id="chart_container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>