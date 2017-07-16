<?php

$this->Html->script(array(
    "jquery.js",
    "bootstrap.min.js",
    "jquery.backstretch.min.js",
), array('block' => 'scripts'));

echo $this->fetch('scripts'); 

?>