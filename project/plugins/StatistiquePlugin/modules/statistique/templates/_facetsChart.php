<script type="text/javascript">
$(function () {
        $('#chart_container').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Statistiques DRM',
                x: -20 //center
            },
            xAxis: {
                categories: ["<?php echo implode('", "', $chartConfig['categories']->getRawValue()) ?>"]
            },
            yAxis: {
                title: {
                    text: 'Hectolitres (hl)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: 'hl'
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