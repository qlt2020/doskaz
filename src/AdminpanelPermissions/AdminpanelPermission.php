<?php


namespace App\AdminpanelPermissions;

abstract class AdminpanelPermission
{
    public const ACCESS = 'adminpanel_access';
    public const OBJECTS_ACCESS = 'objects_access';
    public const ADDING_REQUESTS_ACCESS = 'adding_requests_access';
    public const USERS_ACCESS = 'users_access';
    public const BLOG_ACCESS = 'blog_access';
    public const COMPLAINTS_ACCESS = 'complaints_access';
    public const REGIONAL_REPRESENTATIVES_ACCESS = 'regional_representatives_access';
    public const REGIONAL_COORDINATORS_ACCESS = 'regional_coordinators_access';
    public const ADMINISTRATION_TASKS_ACCESS = 'administration_tasks_access';
    public const FEEDBACK_ACCESS = 'feedback_access';

    public const ALL = [
        self::ACCESS,
        self::OBJECTS_ACCESS,
        self::ADDING_REQUESTS_ACCESS,
        self::USERS_ACCESS,
        self::BLOG_ACCESS,
        self::COMPLAINTS_ACCESS,
        self::REGIONAL_REPRESENTATIVES_ACCESS,
        self::REGIONAL_COORDINATORS_ACCESS,
        self::ADMINISTRATION_TASKS_ACCESS,
        self::FEEDBACK_ACCESS,
    ];
}
