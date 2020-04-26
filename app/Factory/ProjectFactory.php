<?php

namespace App\Factory;

use App\Project;

class ProjectFactory
{
    public static function create(int $user_id, int $customer_id, int $account_id): Project
    {
        $project = new Project;
  
        $project->customer_id = $customer_id;
        $project->account_id = $account_id;
        $project->user_id = $user_id;
        
        return $project;
    }
}
