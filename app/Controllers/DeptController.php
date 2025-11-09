<?php

namespace App\Controllers;

use App\Models\Repositories\DepartmentRepository;

class DeptController
{
    public function __construct(private DepartmentRepository $departments = new DepartmentRepository())
    {
    }

    public function index(): void
    {
        $items = $this->departments->allActive();
        view('admin/departments', [
            'title' => 'واحدهای سازمانی',
            'departments' => $items,
        ]);
    }
}
