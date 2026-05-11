<?php

namespace App\Support;

class TrainerPermissions
{
    public const PERMISSIONS = [
        'equipment-list', 'equipment-add', 'equipment-edit', 'equipment-delete',
        'exercise-list', 'exercise-add', 'exercise-edit', 'exercise-delete',
        'level-list',
        'workout-list', 'workout-add', 'workout-edit', 'workout-delete',
        'diet-list', 'diet-add', 'diet-edit', 'diet-delete',
        'bodyparts-list', 'bodyparts-add', 'bodyparts-edit', 'bodyparts-delete',
        'classschedule-list', 'classschedule-add', 'classschedule-edit', 'classschedule-delete',
        'package-list', 'package-add', 'package-edit', 'package-delete',
        'subscription-list', 'subscription-add',
        'user-list', 'user-add', 'user-edit', 'user-delete', 'user-show',
    ];
}
