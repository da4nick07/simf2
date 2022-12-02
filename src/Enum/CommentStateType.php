<?php
namespace App\Enum;

enum CommentStateType : int
{
    case SUBMITTED = 0;
    case PUBLISHED = 1;
    case SPAM = 2;
 }
