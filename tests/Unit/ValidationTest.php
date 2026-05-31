<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class ValidationTest extends TestCase
{
    public function test_email_validation_required()
    {
        $validator = Validator::make(
            ['email' => ''],
            ['email' => 'required|email']
        );
        $this->assertTrue($validator->fails());
    }

    public function test_email_validation_valid_format()
    {
        $validator = Validator::make(
            ['email' => 'valid@example.com'],
            ['email' => 'required|email']
        );
        $this->assertTrue($validator->passes());
    }

    public function test_consultation_fee_must_be_numeric()
    {
        $validator = Validator::make(
            ['fee' => 'abc'],
            ['fee' => 'required|numeric|min:0']
        );
        $this->assertTrue($validator->fails());
    }

    public function test_consultation_fee_accepts_valid_number()
    {
        $validator = Validator::make(
            ['fee' => 150],
            ['fee' => 'required|numeric|min:0|max:1000']
        );
        $this->assertTrue($validator->passes());
    }
}