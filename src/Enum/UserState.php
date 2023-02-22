<?php
namespace App\Enum;

enum UserState: string
{
    case All = 'Все';
    case Enabled = 'Подтверждённые';
    case NotEnabled = 'Неподтверждённые';
}
