<?php

$framework = TD_Framework::getInstance();

add_action('wp_footer',array($framework,'add_socials'));