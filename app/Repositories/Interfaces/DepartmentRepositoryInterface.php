<?php

namespace App\Repositories\Interfaces;

interface DepartmentRepositoryInterface extends RepositoryInterface
{
    public function allActive();

    public function allVisible();

    public function allOrdered();
}
