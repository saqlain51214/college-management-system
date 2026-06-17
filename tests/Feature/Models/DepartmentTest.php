<?php

namespace Tests\Feature\Models;

use App\Enums\DepartmentTypeEnum;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_department_can_be_created(): void
    {
        $dept = Department::create([
            'name' => 'Computer Science',
            'code' => 'DEPT-CS',
            'type' => DepartmentTypeEnum::Academic->value,
        ]);

        $this->assertDatabaseHas('departments', ['code' => 'DEPT-CS']);
        $this->assertSame('Computer Science', $dept->name);
    }

    public function test_slug_is_auto_generated_from_name(): void
    {
        $dept = Department::create([
            'name' => 'Department of Computer Science',
            'code' => 'DEPT-CS',
            'type' => DepartmentTypeEnum::Academic->value,
        ]);

        $this->assertSame('department-of-computer-science', $dept->slug);
    }

    public function test_explicit_slug_is_preserved(): void
    {
        $dept = Department::create([
            'name' => 'Computer Science',
            'slug' => 'cs-dept',
            'code' => 'DEPT-CS',
            'type' => DepartmentTypeEnum::Academic->value,
        ]);

        $this->assertSame('cs-dept', $dept->slug);
    }

    public function test_active_scope_returns_only_active_departments(): void
    {
        Department::create(['name' => 'Active Dept', 'code' => 'A', 'type' => DepartmentTypeEnum::Academic->value, 'is_active' => true]);
        Department::create(['name' => 'Inactive Dept', 'code' => 'B', 'type' => DepartmentTypeEnum::Academic->value, 'is_active' => false]);

        $active = Department::active()->get();

        $this->assertCount(1, $active);
        $this->assertSame('Active Dept', $active->first()->name);
    }

    public function test_visible_scope_excludes_hidden_departments(): void
    {
        Department::create(['name' => 'Public Dept', 'code' => 'P', 'type' => DepartmentTypeEnum::Academic->value, 'is_active' => true, 'show_on_website' => true]);
        Department::create(['name' => 'Hidden Dept', 'code' => 'H', 'type' => DepartmentTypeEnum::Academic->value, 'is_active' => true, 'show_on_website' => false]);

        $visible = Department::visible()->get();

        $this->assertCount(1, $visible);
        $this->assertSame('Public Dept', $visible->first()->name);
    }

    public function test_ordered_scope_sorts_by_sort_order_then_name(): void
    {
        Department::create(['name' => 'Zebra Dept', 'code' => 'Z', 'type' => DepartmentTypeEnum::Academic->value, 'sort_order' => 1]);
        Department::create(['name' => 'Alpha Dept', 'code' => 'AL', 'type' => DepartmentTypeEnum::Academic->value, 'sort_order' => 2]);

        $ordered = Department::ordered()->get();

        $this->assertSame('Zebra Dept', $ordered->first()->name);
    }

    public function test_soft_delete_does_not_permanently_remove_record(): void
    {
        $dept = Department::create(['name' => 'Delete Me', 'code' => 'DEL', 'type' => DepartmentTypeEnum::Academic->value]);
        $id   = $dept->id;

        $dept->delete();

        $this->assertSoftDeleted('departments', ['id' => $id]);
        $this->assertDatabaseHas('departments', ['id' => $id]);
    }

    public function test_status_accessor_returns_active_when_is_active_true(): void
    {
        $dept = Department::create(['name' => 'Test', 'code' => 'T', 'type' => DepartmentTypeEnum::Academic->value, 'is_active' => true]);

        $this->assertSame('Active', $dept->status->label());
    }
}
