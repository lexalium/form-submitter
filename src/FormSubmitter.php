<?php

declare(strict_types=1);

namespace Lexal\FormSubmitter;

use Lexal\FormSubmitter\Exception\SubmitterNotFoundException;

use function array_unshift;

final class FormSubmitter implements FormSubmitterInterface
{
    /**
     * @var FormSubmitterInterface[] $submitters
     */
    private readonly array $submitters;

    public function __construct(FormSubmitterInterface $submitter, FormSubmitterInterface ...$submitters)
    {
        array_unshift($submitters, $submitter);

        $this->submitters = $submitters;
    }

    public function supportsSubmitting(mixed $entity): bool
    {
        return $this->getSubmitter($entity) !== null;
    }

    /**
     * @inheritDoc
     *
     * @throws SubmitterNotFoundException
     */
    public function submit(mixed $entity): mixed
    {
        $submitter = $this->getSubmitter($entity);

        if ($submitter === null) {
            throw new SubmitterNotFoundException($entity);
        }

        return $submitter->submit($entity);
    }

    private function getSubmitter(mixed $entity): ?FormSubmitterInterface
    {
        foreach ($this->submitters as $submitter) {
            if ($submitter->supportsSubmitting($entity)) {
                return $submitter;
            }
        }

        return null;
    }
}
