<?php

/**
 * PHP console application that scrapes the BBC news homepage 
 * (http://www.bbc.co.uk/news/) and returns a JSON array of the 
 * most popular shared articles table.
 * 
 * @author Thiago Marini
 * @link https://github.com/thiagomarini/bbc-scrapper
 * London 2014-12-31
 */
require_once 'Bootstrap.php';

header('Content-Type: application/json');

$s = new Scrapper;
$s->loadArticles();
echo $s->returnJson();
