<?php

namespace App\Support;

class PermissionMap
{
    /**
     * Get the configured permission modules.
     */
    public static function modules(): array
    {
        return config('permissions.modules', []);
    }

    /**
     * Flatten module/action pairs to permission strings.
     */
    public static function allPermissions(): array
    {
        $permissions = [];

        foreach (self::modules() as $moduleKey => $module) {
            $actions = $module['actions'] ?? [];

            foreach (array_keys($actions) as $actionKey) {
                $permissions[] = "{$moduleKey}.{$actionKey}";
            }
        }

        return $permissions;
    }
}
