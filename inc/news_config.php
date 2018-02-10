<?php

global $terekhin_framework;

add_filter('qode_themename_filter_blog_template_read_more_button',array($terekhin_framework,'translate_read_more'),10,1);
