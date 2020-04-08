<?php
require_once('Git.php');

$repo = Git::open('.');
var_dump($repo);
?>