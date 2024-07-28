<?php

namespace App\Exceptions;

use Exception;

class FrontmatterMissingException extends Exception
{
    public function __construct()
    {
        $message = 'Frontmatter missing in post.';

        parent::__construct($message);
    }
}
