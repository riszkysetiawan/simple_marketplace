<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Illuminate\Support\Str;

abstract class BaseResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        try {
            if (! auth()->check()) {
                return false;
            }

            $permission = static::getPermissionName('view_any');
            return auth()->user()->can($permission);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function canViewAny(): bool
    {
        try {
            if (! auth()->check()) {
                return false;
            }

            $permission = static::getPermissionName('view_any');
            return auth()->user()->can($permission);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function canCreate(): bool
    {
        try {
            if (! auth()->check()) {
                return false;
            }

            $permission = static::getPermissionName('create');
            return auth()->user()->can($permission);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function canEdit($record): bool
    {
        try {
            if (! auth()->check()) {
                return false;
            }

            $permission = static::getPermissionName('update');
            return auth()->user()->can($permission);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function canDelete($record): bool
    {
        try {
            if (! auth()->check()) {
                return false;
            }

            $permission = static::getPermissionName('delete');
            return auth()->user()->can($permission);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected static function getPermissionName(string $action): string
    {
        $model = class_basename(static::getModel());
        $modelName = Str::snake($model);
        return "{$action}_{$modelName}";
    }
}
