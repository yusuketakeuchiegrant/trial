<?php
require_once('Git.php');

/**
 * 
 */
class GitAutoMerger
{
    public function merge_to_sale_dev($branch)
    {
        $sale_dev = '_sale_dev';

        if ($branch === $sale_dev || $branch === 'master') {
            return false;
        }

        $path_to_kozuchi_repo = '.';
        $repo = Git::open($path_to_kozuchi_repo);
        $active_branch = $repo->active_branch();
        $local_branches = $repo->list_branches();
        $remote_branches = $repo->list_remote_branches();

        if ($active_branch !== $sale_dev) {
            if (in_array($sale_dev, $local_branches)) {
                $repo->checkout($sale_dev);
            } else {
                if (!in_array($sale_dev, $remote_branches)) {
                    throw new Exception(sprintf('The branch %s not found', $sale_dev));
                }
                $repo->checkout_remote($sale_dev);
            }
        }

        // If $branch exists, delete it
        if (in_array($branch, $local_branches)) {
            $repo->delete_branch($branch, $force=true);
        }
var_dump($remote_branches);
        // Checkout $branch from remote branch
        if (in_array($branch, $remote_branches)) {
            $repo->checkout_remote($branch);
        } else {
            throw new Exception(sprintf('The branch %s not found', $branch));
        }

        // Merge $branch to sale_dev
        $merge_result = $repo->merge($branch);
        var_dump($merge_result);
        $result = false;
        // If conflicted
        if(true) {
            // Reset $branch
        } else {
            $result = true;
        }

        // Finally, delete $branch
        $repo->delete_branch($branch, $force=true);

        return $result;

        /**
         * とりあえずsale_devにcheckout したほうが良さそう
         * $branch is in remote branches
         * checkout remote branch $branch
         * checkout [remote] branch_sale_dev
         * merge($branch)
         *  commit
         *  push
         *  rm
         *  return true
         * 
         *  reset hard
         *  rm
         *  return false
         */
    }    
}

$gam = new GitAutoMerger();
$gam->merge_to_sale_dev('branch');
?>