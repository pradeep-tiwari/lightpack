<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Lightpack\Validator\Validator;

final class ValidatorTest extends TestCase
{
    public function testHasValidationErrors()
    {
        $data = [
            'name' => 'ma',
            'email' => 'hello@world',
        ];

        $rules = [
            'name' => 'required|min:3|max:12',
            'email' => 'required|email'
        ];

        $validator = new Validator($data);
        $validator->setrules($rules)->run();

        $this->assertTrue($validator->hasErrors());
    }

    public function testHasNoValidationErrors()
    {
        $data = [
            'name' => 'maxim',
            'email' => 'hello@world',
        ];

        $rules = [
            'name' => 'required|min:3|max:12',
            'email' => 'required|email'
        ];

        $validator = new Validator($data);
        $validator->setrules($rules)->run();

        $this->assertTrue($validator->hasErrors());
    }

    public function testCreatesAppropriateErrorMessages()
    {
        $data = ['password'  => 'hello', 'email' => 'hello@example.com'];
        $rules = ['password' => 'min:6', 'email' => 'email'];

        $validator = new Validator($data);
        $validator->setRules($rules)->run();

        $this->assertCount(1, $validator->getErrors());
        $this->assertTrue($validator->getError('email') === '');
        $this->assertTrue($validator->getError('password') !== '');
    }

    public function testCanSetRulesIndividually()
    {
        $validator = new Validator([
            'phone' => '091234521',
            'fname' => 'Bob123',
            'lname' => 'Williams'
        ]);

        $validator
            ->setRule('phone', 'required|length:10')
            ->setRule('fname', [
                'rules' => 'required|alpha',
                'label' => 'First name',
            ])
            ->setRule('lname', [
                'rules' => 'required|alpha',
                'error' => 'Last name should be your title.'
            ])
            ->run();

        $errors = $validator->getErrors();

        $this->assertTrue($validator->hasErrors());
        $this->assertCount(2, $errors);
        $this->assertTrue(isset($errors['phone']));
        $this->assertTrue(isset($errors['fname']));
        $this->assertFalse(isset($errors['lname']));
    }

    public function testValidationRuleRequired()
    {
        // Assertion 1
        $validator = new Validator(['password' => null]);
        $validator->setRule('password', 'required')->run();

        $this->assertTrue($validator->hasErrors());

        // Assertion 2
        $validator = new Validator(['password' => 'hello']);
        $validator->setRule('password', 'required')->run();

        $this->assertFalse($validator->hasErrors());
    }

    public function testValidationRuleAlpha()
    {
        // Assertion 1
        $validator = new Validator(['name' => 'Bob123']);
        $validator->setRule('name', 'alpha')->run();

        $this->assertTrue($validator->hasErrors());

        // Assertion 2
        $validator = new Validator(['name' => 'Bob']);
        $validator->setRule('name', 'alpha')->run();

        $this->assertFalse($validator->hasErrors());
    }

    public function testValidationRuleAlnum()
    {
        // Assertion 1
        $validator = new Validator(['name' => '@Bob123']);
        $validator->setRule('name', 'alnum')->run();

        $this->assertTrue($validator->hasErrors());

        // Assertion 2
        $validator = new Validator(['name' => 'Bob123']);
        $validator->setRule('name', 'alnum')->run();

        $this->assertFalse($validator->hasErrors());
    }

    public function testValidationRuleEmail()
    {
        // Assertion 1
        $validator = new Validator(['email' => 'hello@example']);
        $validator->setRule('email', 'email')->run();

        $this->assertTrue($validator->hasErrors());

        // Assertion 2
        $validator = new Validator(['email' => 'hello@example.co.in']);
        $validator->setRule('email', 'email')->run();

        $this->assertFalse($validator->hasErrors());
    }

    public function testValidationRuleSlug()
    {
        // Assertion 1
        $validator = new Validator(['slug' => 'hello%world']);
        $validator->setRule('slug', 'slug')->run();

        $this->assertTrue($validator->hasErrors());

        // Assertion 2
        $validator = new Validator(['slug' => 'hello-world']);
        $validator->setRule('slug', 'slug')->run();

        $this->assertFalse($validator->hasErrors());
    }

    public function testValidationRuleUrl()
    {
        // Assertion 1
        $validator = new Validator(['url' => 'http://example:8080']);
        $validator->setRule('url', 'url')->run();

        // Assertion 2
        $validator = new Validator(['url' => 'http://example']);
        $validator->setRule('url', 'url')->run();

        $this->assertFalse($validator->hasErrors());

        // Assertion 3
        $validator = new Validator(['url' => 'http://example.com']);
        $validator->setRule('url', 'url')->run();

        $this->assertFalse($validator->hasErrors());

        // Assertion 4
        $validator = new Validator(['url' => 'http://123.example.com']);
        $validator->setRule('url', 'url')->run();

        $this->assertFalse($validator->hasErrors());

        // Assertion 5
        $validator = new Validator(['url' => 'http//example.com']);
        $validator->setRule('url', 'url')->run();

        $this->assertTrue($validator->hasErrors());
    }
}
