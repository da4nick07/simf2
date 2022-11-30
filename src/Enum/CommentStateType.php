<?php
namespace App\Enum;

enum CommentStateType : int
{
    case SUBMITTED = 0;
    case SPAM = 1;
    case PUBLISHED = 2;
}
