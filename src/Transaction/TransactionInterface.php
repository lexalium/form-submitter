<?php

declare(strict_types=1);

namespace Lexal\FormSubmitter\Transaction;

interface TransactionInterface
{
    /**
     * Starts a new transaction.
     */
    public function start(): void;

    /**
     * Commits active transaction.
     */
    public function commit(): void;

    /**
     * Rollbacks active transaction.
     */
    public function rollback(): void;
}
