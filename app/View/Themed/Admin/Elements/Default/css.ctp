<?php 

$this->Html->css(array(
    "bootstrap.css",
    "../font-awesome/css/font-awesome.css",
    "style.css",
    "style-responsive.css"
), array('block' => 'css'));

echo $this->fetch('css');

?>