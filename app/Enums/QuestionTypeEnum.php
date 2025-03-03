<?php

namespace App\Enums;

enum QuestionTypeEnum: string {

    case Text = 'Text';
    case Image = 'Image';
    case Audio = 'Audio';
    case Video = 'Video';
}
