<?php

declare(strict_types=1);

namespace Lexal\FormSubmitter;

use Lexal\FormSubmitter\Transaction\TransactionInterface;
use Throwable;

final class TransactionalFormSubmitter implements FormSubmitterInterface
{
    public function __construct(
        private readonly FormSubmitterInterface $formSubmitter,
        private readonly TransactionInterface $transaction,
    ) {
    }

    public function supportsSubmitting(mixed $entity): bool
    {
        return $this->formSubmitter->supportsSubmitting($entity);
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function submit(mixed $entity): mixed
    {
        $this->transaction->start();

        try {
            $result = $this->formSubmitter->submit($entity);
        } catch (Throwable $exception) {
            $this->transaction->rollback();

            throw $exception;
        }

        $this->transaction->commit();

        return $result;
    }
}
