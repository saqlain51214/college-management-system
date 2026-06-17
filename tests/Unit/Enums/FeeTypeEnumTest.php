<?php

namespace Tests\Unit\Enums;

use App\Enums\FeeTypeEnum;
use PHPUnit\Framework\TestCase;

class FeeTypeEnumTest extends TestCase
{
    public function test_all_fee_types_exist(): void
    {
        $values = array_column(FeeTypeEnum::cases(), 'value');

        $this->assertContains('tuition', $values);
        $this->assertContains('admission', $values);
        $this->assertContains('exam', $values);
        $this->assertContains('library', $values);
        $this->assertContains('sports', $values);
        $this->assertContains('lab', $values);
        $this->assertContains('hostel', $values);
        $this->assertContains('transport', $values);
        $this->assertContains('other', $values);
    }

    public function test_tuition_value(): void
    {
        $this->assertSame('tuition', FeeTypeEnum::Tuition->value);
    }

    public function test_can_create_from_string(): void
    {
        $type = FeeTypeEnum::from('exam');
        $this->assertSame(FeeTypeEnum::Exam, $type);
    }

    public function test_try_from_invalid_returns_null(): void
    {
        $this->assertNull(FeeTypeEnum::tryFrom('invalid_type'));
    }

    public function test_enum_count(): void
    {
        $this->assertCount(9, FeeTypeEnum::cases());
    }
}
