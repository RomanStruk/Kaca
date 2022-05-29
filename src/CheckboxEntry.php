<?php

declare(strict_types=1);

namespace Kaca;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CheckboxEntry implements Contracts\CheckboxEntries
{
    protected ?string $tag = null;

    /**
     * Збереження даних
     *
     * @param string $type
     * @param array|string|null $content
     * @param string|null $tag
     * @return void
     */
    public function createRecord(string $type, $content, ?string $tag = null): void
    {
        $this->query()->create([
            'tag' => $tag ?? $this->tag,
            'type' => $type,
            'content' => $content ?? [],
        ]);
    }

    protected function query(): Builder
    {
        return Models\CheckboxEntry::query();
    }

    /**
     * Логи запитів з сервісу checkbox.ua
     *
     * @param Model|string $tag
     * @param string|null $search
     * @return LengthAwarePaginator
     */
    public function paginateWith($tag = null, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->query();
        if (!is_null($search)) {
            $query->where('content', 'LIKE', "%{$search}%")
                ->orWhere('tag', 'LIKE', "%{$search}%");
        }
        if (is_string($tag)) {
            $tag = trim($tag);
            $query->where('tag', 'LIKE', "%{$tag}%");
        } elseif (is_object($tag)) {
            $query->where(
                'tag',
                'LIKE',
                strtolower(class_basename($tag)) . ':' . ($tag->uuid ?? $tag->id) . '%'
            );
        }
        return $query->latest()->paginate(15);
    }

    /**
     * Set tag for records
     *
     * @param string $tag
     * @return $this
     */
    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
