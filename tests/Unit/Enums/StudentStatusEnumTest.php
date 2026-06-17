<?php

namespace Tests\Unit\Enums;

use App\Enums\StudentStatusEnum;
use PHPUnit\Framework\TestCase;

class StudentStatusEnumTest extends TestCase
{
    public function test_active_case_has_correct_value(): void
    {
        $this->assertSame('active', StudentStatusEnum::Active->value);
    }

    public function test_all_cases_exist(): void
    {
        $values = array_column(StudentStatusEnum::cases(), 'value');

        $this->assertContains('active', $values);
        $this->assertContains('inactive', $values);
        $this->assertContains('graduated', $values);
        $this->assertContains('dropped', $values);
        $this->assertContains('expelled', $values);
        $this->assertContains('transferred', $values);
        $this->assertContains('on_leave', $values);
    }

    public function test_active_label(): void
    {
        $this->assertSame('Active', StudentStatusEnum::Active->label());
    }

    public function test_on_leave_label(): void
    {
        $this->assertSame('On Leave', StudentStatusEnum::OnLeave->label());
    }

    public function test_graduated_label(): void
    {
        $this->assertSame('Graduated', StudentStatusEnum::Graduated->label());
    }

    public function test_active_color_is_success(): void
    {
        $this->assertSame('success', StudentStatusEnum::Active->color());
    }

    public function test_expelled_color_is_danger(): void
    {
        $this->assertSame('danger', StudentStatusEnum::Expelled->color());
    }

    public function test_graduated_color_is_info(): void
    {
        $this->assertSame('info', StudentStatusEnum::Graduated->color());
    }

    public function test_active_icon_is_heroicon(): void
    {
        $this->assertStringStartsWith('heroicon-', StudentStatusEnum::Active->icon());
    }

    public function test_can_create_from_value(): void
    {
        $status = StudentStatusEnum::from('active');
        $this->assertSame(StudentStatusEnum::Active, $status);
    }

    public function test_try_from_returns_null_for_invalid_value(): void
    {
        $this->assertNull(StudentStatusEnum::tryFrom('unknown_status'));
    }
}
