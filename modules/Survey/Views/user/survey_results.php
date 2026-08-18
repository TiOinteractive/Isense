<div id="survey-chart"></div>
<script src="/adm/third-party/apexcharts/dist/apexcharts.min.js"></script>
<?php
$rdata = array();
 if(!empty($data)) {
        foreach($data as $o) {
            $rdata[] = array(
                'x' => $o['option'],
                'y' => !empty($o['count']) ? intval($o['count']) : 0
            );
        }
    }
?>				


<script>
new ApexCharts(document.querySelector('#survey-chart'), {
                    chart: {
                      type: 'bar'
                    },
                    legend: {
                      position: 'bottom',
                    },	
                    series: [{
                        name: "<?=lang('Survey.Votes'); ?>",
                        data: <?=json_encode($rdata);?>,
                        color: "#ffc800",
                    }],
                    xaxis: {
                        labels: {
                            rotate: 0,
                            trim: true,
                        }
                    }
                  }).render();
</script>				  











