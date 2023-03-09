<?php
namespace App\Enum;

enum CommentStateType : int
{
    case DRAFT = 0;
    case SUBMITTED = 1;
    case SPAM = 2;
    case HAM = 3;
    case REJECTED = 4;
    case PUBLISHED = 5;

    public function getName() : string
    {
        return match ($this) {
            CommentStateType::DRAFT => 'Черновики',
            CommentStateType::SUBMITTED => 'Отправленные',
            CommentStateType::SPAM => 'Спам',
            CommentStateType::HAM => 'HAM',
            CommentStateType:: REJECTED => 'Отклонённые',
            CommentStateType::PUBLISHED => 'Опубликованные',
        };
    }
}
