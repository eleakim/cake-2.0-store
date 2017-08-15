<?php

$this->Html->script(array(
    "jquery.js",
    "jquery-1.8.3.min.js",
    "bootstrap.min.js",
    "jquery.backstretch.min.js",
    "chart-master/Chart.js",
    "jquery.dcjqaccordion.2.7.js",
    "jquery.scrollTo.min.js",
    "jquery.nicescroll.js",
    "jquery.sparkline.js",
    "common-scripts.js",
    "gritter/js/jquery.gritter.js",
    "gritter-conf.js",
    "sparkline-chart.js",
	"zabuto_calendar.js",
), array('block' => 'scripts'));

echo $this->fetch('scripts'); 

?>

    