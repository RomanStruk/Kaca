<?php

declare(strict_types=1);

namespace Kaca;

use Illuminate\Contracts\Auth\Authenticatable;
use Kaca\Models\Action;

class ActionRecorder
{
    public const CREATE = 'Create';
    public const UPDATE = 'Update';
    public const DELETE = 'Delete';


    public function findUserForAction(string $class, string $action = self::CREATE): Action
    {
        return Action::where('tag', '=', class_basename($class) . $action)->firstOrNew();
    }

    public static function creating(Authenticatable $authenticatable, string $class, string $uuid): void
    {
        (new self())->create(
            $authenticatable->getAuthIdentifier(),
            class_basename($class) . self::CREATE,
            $uuid
        );
    }

    public static function updating(Authenticatable $authenticatable, string $class, string $uuid): void
    {
        (new self())->create(
            $authenticatable->getAuthIdentifier(),
            class_basename($class) . self::UPDATE,
            $uuid
        );
    }

    public static function deleting(Authenticatable $authenticatable, string $class, string $uuid): void
    {
        (new self())->create(
            $authenticatable->getAuthIdentifier(),
            class_basename($class) . self::DELETE,
            $uuid
        );
    }

    protected function create(int $userId, string $tag, string $target): Action
    {
        return Action::query()->create([
            'tag' => $tag,
            'user_id' => $userId,
            'target' => $target,
        ]);
    }
}
