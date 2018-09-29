<?php declare(strict_types = 1);

namespace App\Model\Enum;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
final class RolesForUserEnum
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public static function getChoices(): array
    {
        return [
            'Administrátor' => self::ROLE_ADMIN,
            'ROOT admin (může se přihlásit na jiné uživatele)' => self::ROLE_SUPER_ADMIN,
        ];
    }
}
