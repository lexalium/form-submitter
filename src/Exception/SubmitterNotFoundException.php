<?php

declare(strict_types=1);

namespace Lexal\FormSubmitter\Exception;

use function get_debug_type;
use function sprintf;

class SubmitterNotFoundException extends FormSubmitterException
{
    public function __construct(mixed $entity)
    {
        parent::__construct(
            sprintf('Could not submit entity [%s], no supporting submitter found.', get_debug_type($entity)),
        );
    }
}
