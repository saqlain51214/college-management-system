<?php

namespace App\Providers;

use App\Repositories\AcademicProgramRepository;
use App\Repositories\AcademicYearRepository;
use App\Repositories\CourseRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\Interfaces\AcademicProgramRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use App\Repositories\Interfaces\SemesterRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\SemesterRepository;
use App\Repositories\StudentRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DepartmentRepositoryInterface::class,       DepartmentRepository::class);
        $this->app->bind(AcademicProgramRepositoryInterface::class,  AcademicProgramRepository::class);
        $this->app->bind(AcademicYearRepositoryInterface::class,     AcademicYearRepository::class);
        $this->app->bind(SemesterRepositoryInterface::class,         SemesterRepository::class);
        $this->app->bind(CourseRepositoryInterface::class,           CourseRepository::class);
        $this->app->bind(StudentRepositoryInterface::class,          StudentRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class,          TeacherRepository::class);
    }
}
