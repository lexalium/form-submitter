# Form Submitter

[![PHPUnit, PHPCS, PHPStan Tests](https://github.com/lexalium/form-submitter/actions/workflows/tests.yml/badge.svg)](https://github.com/lexalium/form-submitter/actions/workflows/tests.yml)

With this package you can submit a form entity, e.g. save it into the database. The package can be used with the
[Stepped Form](https://github.com/lexalium/stepped-form) package on FormFinished event.

## Requirements

**PHP:** >=8.1

## Installation

Via Composer

```
composer require lexal/form-submitter
```

## Usage

1. Create a Form Submitter for the specific entity. Form Submitter can return data that will be passed back where
   `submit` method has been called.
   ```php
   use Lexal\FormSubmitter\FormSubmitterInterface;
   
   final class CustomerFormSubmitter implements FormSubmitterInterface
   {
       public function supportsSubmitting(mixed $entity): bool
       {
           return $entity instanceof Customer;
       }
       
       public function submit(mixed $entity): mixed
       {
           // save entity to the database
           
           return $entity;
       }
   }
   ```

2. Use Form Submitter in your application.
   ```php
   $entity = new Customer();
   $formSubmitter = new CustomerFormSubmitter();
   
   if ($formSubmitter->supportsSubmitting($entity)) {
       $formSubmitter->submit($entity);
   }
   ```

You can use the following build-in Form Submitters:
1. `FormSubmitter` - contains array of submitters and submit a form entity to the first one that supports submitting.
   ```php
   use Lexal\FormSubmitter\FormSubmitter;

   $formSubmitter = new FormSubmitter(
       new CustomerFormSubmitter(),
   );

   $formSubmitter->submit(new Customer());
   ```

2. `TransactionalFormSubmitter` - submits a form entity in the transaction (e.g. database transaction).
   ```php
   use Lexal\FormSubmitter\FormSubmitter;
   use Lexal\FormSubmitter\Transaction\TransactionInterface;
   use Lexal\FormSubmitter\TransactionalFormSubmitter;

   final class DatabaseTransaction implements TransactionInterface
   {
        public function start(): void
        {
            // start transaction
        }

        public function commit(): void
        {
            // commit transaction
        }

        public function rollback(): void
        {
            // rollback transaction
        }
   }

   $submitter = new TransactionalFormSubmitter(
        new FormSubmitter(new CustomerFormSubmitter()),
        new DatabaseTransaction(),
   );

   $submitter->submit(new Customer());
   ```

---

## License

Form Submitter is licensed under the MIT License. See [LICENSE](LICENSE) for the full license text.
