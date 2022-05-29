<?php

declare(strict_types=1);

namespace Kaca\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface CheckboxEntries
{
    /**
     * Store data
     *
     * @param string $type
     * @param array|string|null $content
     * @param string|null $tag
     * @return void
     */
    public function createRecord(string $type, $content, ?string $tag = null): void;

    /**
     * Paginate data for tag checkbox.ua
     *
     * @param Model|string $tag
     * @param string|null $search
     * @return LengthAwarePaginator
     */
    public function paginateWith($tag = null, ?string $search = null): LengthAwarePaginator;

    /**
     * Set tag for records
     *
     * @param string $tag
     * @return CheckboxEntries
     */
    public function setTag(string $tag): CheckboxEntries;
}
