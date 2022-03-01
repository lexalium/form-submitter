<?php

declare(strict_types=1);

namespace Lexal\FormSubmitter;

interface FormSubmitterInterface
{
    /**
     * Checks if the entity can be processed by submitter.
     */
    public function supportsSubmitting(mixed $entity): bool;

    /**
     * Runs business logic on form finished event.
     */
    public function submit(mixed $entity): mixed;
}
