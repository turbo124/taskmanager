<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use App\Models\Quote;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Models\Invoice;
use App\Requests\SearchRequest;
use Illuminate\Support\Collection;

interface QuoteRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * @param int $id
     * @return Quote
     */
    public function findQuoteById(int $id): Quote;

    /**
     * @param SearchRequest $search_request
     * @param \App\Models\Account $account
     * @return mixed
     */
    public function getAll(SearchRequest $search_request, Account $account);

    /**
     * @param array $data
     * @param \App\Models\Quote $quote
     * @return Quote|null
     */
    public function createQuote(array $data, Quote $quote): ?Quote;

    /**
     * @param array $data
     * @param Quote $quote
     * @return Quote|null
     */
    public function updateQuote(array $data, Quote $quote): ?Quote;

    /**
     * @param $data
     * @param \App\Models\Quote $quote
     * @return Quote|null
     */
    public function save($data, Quote $quote): ?Quote;
}
