<?php
require_once('Git.php');

/**
 * 
 */
class GitAutoMerger
{
    public function merge_to_sale_dev($blanch='')
    {
        $path_to_kozuchi_repo = '.';
        $repo = Git::open($path_to_kozuchi_repo);

        $active_blanch = $repo->active_branch();
        var_dump($active_blanch);
        $sale_dev = '_sale_dev';
        if ($active_blanch !== $sale_dev) {
            // If this fails, throws exception.
            $repo->checkout($sale_dev);
        }

        $remote_branches = $repo->list_remote_branches();
        if (in_array($blanch, $remote_branches)) {

        }

        $blanch = '';
    }    
}


$gam = new GitAutoMerger();
$gam->merge_to_sale_dev();
?>