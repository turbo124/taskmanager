<?php

namespace App\Repositories\Interfaces;

use App\Models\CaseTemplate;
use App\Models\Product;
use App\Repositories\Base\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface CaseTemplateRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $data
     * @param CaseTemplate $template
     * @return CaseTemplate
     */
    public function save(array $data, CaseTemplate $template): CaseTemplate;

    /**
     * @param int $id
     * @return CaseTemplate
     */
    public function findCaseTemplateById(int $id): CaseTemplate;

   
}
