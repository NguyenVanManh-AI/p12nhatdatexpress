<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserViolateAction extends Enum
{
    CONST PROJECT_COMMENT_FORBIDDEN_WORD = 'project-comment-forbidden-word';
    CONST PERSONAL_POST_FORBIDDEN_WORD = 'personal-post-forbidden-word';
    CONST PERSONAL_COMMENT_FORBIDDEN_WORD = 'personal-comment-forbidden-word';
    CONST CLASSIFIED_FORBIDDEN_WORD = 'classified-forbidden-word';
    CONST CLASSIFIED_COMMENT_FORBIDDEN_WORD = 'classified-comment-forbidden-word';
}
