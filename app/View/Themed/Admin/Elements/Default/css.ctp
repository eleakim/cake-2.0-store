<?php 

$this->Html->css(array(
    "bootstrap.css",
    "../font-awesome/css/font-awesome.css",
    "style.css",
    "style-responsive.css",
    "zabuto_calendar.css",
    "../js/gritter/css/jquery.gritter.css",
    "../lineicons/style.css"
), array('block' => 'css'));

echo $this->fetch('css');

?>