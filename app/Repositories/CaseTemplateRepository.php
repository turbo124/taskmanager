<?php

namespace App\Repositories;

use App\Models\CaseTemplate;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Interfaces\CaseTemplateRepositoryInterface;
use Exception;
use Illuminate\Support\Collection;

class CaseTemplateRepository extends BaseRepository implements BrandRepositoryInterface
{
    /**
     * BrandRepository constructor.
     * @param Brand $brand
     */
    public function __construct(CaseTemplate $template)
    {
        parent::__construct($template);
        $this->model = $template;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $data
     * @param CaseTemplate $template
     * @return Brand
     */
    public function save(array $data, CaseTemplate $template): CaseTemplate
    {
        $template->fill($data);
        $template->save();
        return $template;
    }

    /**
     * @param int $id
     * @return CaseTemplate
     */
    public function findCaseTemplateById(int $id): CaseTemplate
    {
        return $this->findOneOrFail($id);
    }
}
