<?php

namespace OffbeatWP\Support\Wordpress;

use OffbeatWP\Content\User\UserModel;

class UserRole
{
    /** @var class-string<UserModel>[] */
    private static $userModels = [];
    /** @var class-string<UserModel> */
    private static $defaultUserModel = UserModel::class;

    /**
     * @param string $userType
     * @param class-string<UserModel> $userModelClass
     */
    public static function registerUserRole(string $userType, string $userModelClass): void
    {
        self::$userModels[$userType] = $userModelClass;
    }

    /** @return class-string<UserModel> */
    public static function getModelByUserRole(string $userType): ?string
    {
        return self::$userModels[$userType] ?? null;
    }

    /** @param class-string<UserModel> $userModelClass */
    public static function getUserRoleByModel(string $userModelClass): string
    {
        return array_search($userModelClass, self::$userModels, true);
    }

    /** @param class-string<UserModel> $userModelClass */
    public static function setDefaultUserModel(string $userModelClass): void
    {
        self::$defaultUserModel = $userModelClass;
    }

    /** @return class-string<UserModel> */
    public static function getDefaultUserModel(): string
    {
        return self::$defaultUserModel;
    }
}