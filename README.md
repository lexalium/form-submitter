# Form Submitter

With this package you can submit a form entity, e.g. save it into the
database. The package can be used with the [Stepped Form]() package on
FormFinished event.

## Requirements

**PHP:** ^8.0

## Installation

Via Composer

```
composer require lexal/form-submitter
```

## Usage

1. Create a Form Submitter for the specific entity. Form Submitter can return
   data that will be passed to the place where `submit` method has been called.

```php
use Lexal\FormSubmitter\FormSubmitterInterface;

class CustomerFormSubmitter implements FormSubmitterInterface
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

You can use the following Form Submitter:
1. `Lexal\FormSubmitter\FormSubmitter` - contains submitters in the property
   and submit a form entity to the first one that supports submitting.

```php
use Lexal\FormSubmitter\FormSubmitter;

$formSubmitter = new FormSubmitter(
    new CustomerFormSubmitter(),
);

$formSubmitter->submit(new Customer());
```

2. `Lexal\FormSubmitter\TransactionalFormSubmitter` - submits a form
   entity in the transaction.

---

## License

Form Submitter is licensed under the MIT License. See
[LICENSE](LICENSE) for the full license text.
