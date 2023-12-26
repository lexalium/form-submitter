<?php

declare(strict_types=1);

namespace Lexal\FormSubmitter\Tests;

use Lexal\FormSubmitter\Exception\SubmitterNotFoundException;
use Lexal\FormSubmitter\FormSubmitter;
use Lexal\FormSubmitter\FormSubmitterInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

use function is_object;

final class FormSubmitterTest extends TestCase
{
    public function testSupportsSubmitting(): void
    {
        $formSubmitter = new FormSubmitter($this->getFormSubmitter());

        $this->assertTrue($formSubmitter->supportsSubmitting(new stdClass()));
        $this->assertFalse($formSubmitter->supportsSubmitting('test'));
    }

    public function testSubmit(): void
    {
        $submitter = $this->createStub(FormSubmitterInterface::class);

        $formSubmitter = new FormSubmitter($submitter);

        $submitter->method('supportsSubmitting')
            ->willReturn(true);

        $submitter->method('submit')
            ->willReturn(['key' => 'string']);

        $this->assertEquals(['key' => 'string'], $formSubmitter->submit('test'));
    }

    public function testSubmitSubmitterNotFoundException(): void
    {
        $this->expectExceptionObject(new SubmitterNotFoundException('test'));

        $submitter = $this->createStub(FormSubmitterInterface::class);

        $formSubmitter = new FormSubmitter($submitter);

        $submitter->method('supportsSubmitting')
            ->willReturn(false);

        $formSubmitter->submit('test');
    }

    private function getFormSubmitter(): FormSubmitterInterface
    {
        return new class implements FormSubmitterInterface {
            public function supportsSubmitting(mixed $entity): bool
            {
                return is_object($entity);
            }

            public function submit(mixed $entity): mixed
            {
                return $entity;
            }
        };
    }
}
