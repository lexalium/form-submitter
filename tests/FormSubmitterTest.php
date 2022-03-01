<?php

declare(strict_types=1);

namespace Lexal\FormSubmitter\Tests;

use Lexal\FormSubmitter\Exception\SubmitterNotFoundException;
use Lexal\FormSubmitter\FormSubmitter;
use Lexal\FormSubmitter\FormSubmitterInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

use function is_object;

class FormSubmitterTest extends TestCase
{
    public function testSupportsSubmitting(): void
    {
        $formSubmitter = new FormSubmitter($this->getFormSubmitter());

        $this->assertTrue($formSubmitter->supportsSubmitting(new stdClass()));
        $this->assertFalse($formSubmitter->supportsSubmitting('test'));
    }

    public function testSubmit(): void
    {
        $submitter = $this->createMock(FormSubmitterInterface::class);

        $formSubmitter = new FormSubmitter($submitter);

        $submitter->expects($this->once())
            ->method('supportsSubmitting')
            ->with('test')
            ->willReturn(true);

        $submitter->expects($this->once())
            ->method('submit')
            ->with('test')
            ->willReturn(['key' => 'string']);

        $this->assertEquals(['key' => 'string'], $formSubmitter->submit('test'));
    }

    public function testSubmitSubmitterNotFoundException(): void
    {
        $this->expectExceptionObject(new SubmitterNotFoundException('test'));

        $submitter = $this->createMock(FormSubmitterInterface::class);

        $formSubmitter = new FormSubmitter($submitter);

        $submitter->expects($this->once())
            ->method('supportsSubmitting')
            ->with('test')
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
