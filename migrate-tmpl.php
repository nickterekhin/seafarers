<?php
/*
  Template Name: Migrate DB
 */

require_once(CHILD_THEME_PATH.'/migrate/Migrate_News.php');

$news_service = Migrate_News::getInstance();
$news_service->setPathToImages(CHILD_THEME_PATH.'/images');
$news_service->routes();