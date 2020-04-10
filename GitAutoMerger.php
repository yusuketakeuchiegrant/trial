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

        // Checkout $branch from remote branch
        $remote_branch = sprintf('origin/%s', $branch);
        if (in_array($remote_branch, $remote_branches)) {
            $repo->checkout_remote($branch);
        } else {
            throw new Exception(sprintf('The branch %s not found', $remote_branch));
        }

        // Checkout sale_dev and update.
        $repo->checkout($sale_dev);
        //$repo->pull('origin', $sale_dev);

        // Merge $branch to sale_dev
        $result = false;
        try {
            $repo->merge($branch);
            $message = sprintf('Merge %s to %s', $branch, $sale_dev);
            $repo->commit($message);
            $repo->push('origin', $sale_dev);
            $result = true;
        } catch(Exception $e) {
            $repo->reset('HEAD', 'hard');
            var_dump($e);
            $result = false;
        } finally {
            // Finally, delete $branch
            $repo->delete_branch($branch, $force=true);
            return $result;
        }
    }    
}

$gam = new GitAutoMerger();
var_dump($gam->merge_to_sale_dev('branch'));
?>