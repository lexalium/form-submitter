<?php

declare(strict_types=1);

namespace Lexal\FormSubmitter\Tests;

use Exception;
use Lexal\FormSubmitter\FormSubmitterInterface;
use Lexal\FormSubmitter\Transaction\TransactionInterface;
use Lexal\FormSubmitter\TransactionalFormSubmitter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TransactionalFormSubmitterTest extends TestCase
{
    private MockObject $transaction;
    private MockObject $formSubmitter;

    private FormSubmitterInterface $transactionalFormSubmitter;

    protected function setUp(): void
    {
        $this->transaction = $this->createMock(TransactionInterface::class);
        $this->formSubmitter = $this->createMock(FormSubmitterInterface::class);

        $this->transactionalFormSubmitter = new TransactionalFormSubmitter(
            $this->formSubmitter,
            $this->transaction,
        );
    }

    public function testSubmit(): void
    {
        $this->transaction->expects($this->once())
            ->method('start');

        $this->transaction->expects($this->never())
            ->method('rollback');

        $this->transaction->expects($this->once())
            ->method('commit');

        $this->formSubmitter->expects($this->once())
            ->method('submit')
            ->willReturn(['key' => 'string']);

        $this->assertEquals(
            ['key' => 'string'],
            $this->transactionalFormSubmitter->submit('test'),
        );
    }

    public function testSubmitRollbackTransaction(): void
    {
        $this->expectExceptionObject(new Exception('Test message'));

        $this->transaction->expects($this->once())
            ->method('start');

        $this->transaction->expects($this->once())
            ->method('rollback');

        $this->transaction->expects($this->never())
            ->method('commit');

        $this->formSubmitter->expects($this->once())
            ->method('submit')
            ->willThrowException(new Exception('Test message'));

        $this->transactionalFormSubmitter->submit('test');
    }
}
