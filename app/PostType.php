<?php

namespace App;

enum PostType: int
{
    case REGULAR = 0;
    case PINNED = 1;
    case PROMOTIONAL = 2;
}
