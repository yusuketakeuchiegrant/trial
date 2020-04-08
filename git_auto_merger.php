<?php
require_once('Git.php');

$repo = Git::open('.');
var_dump($repo);
$result = $repo->test_git();
var_dump($result);
$result = $repo->commit('Add the git plugin.');
var_dump($result);
$branch_list = $repo->list_branches();
var_dump($branch_list);
$repo->checkout('master');
?>