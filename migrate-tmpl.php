<?php
/*
  Template Name: Migrate DB
 */

require_once(CHILD_THEME_PATH.'/migrate/Migrate_News.php');

$news_service = Migrate_News::getInstance();
$news_service->setPathToImages('/home/nickterekhin/seafarersjournal.com/uploads/news/thumb');
$news_service->routes();