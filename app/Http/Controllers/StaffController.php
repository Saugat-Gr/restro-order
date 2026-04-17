<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Enums\UserRole;
use App\Http\Requests\Staffs\CreateRequest;
use App\Http\Requests\Staffs\UpdateRequest;
use App\Models\User;
use App\Services\StaffService;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class StaffController extends Controller
{

    public function __construct(protected StaffService $staffService)
    {

        $this->middleware('permission:view-staffs', ['only' => ['index']]);
        $this->middleware('permission:create-staffs', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-staffs', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-staffs', ['only' => ['destroy']]);


    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staffs = $this->staffService->getStaffs();


        return Inertia::render('Restaurant/Staffs/Index', [
            "app" => [
                "title" => "Staffs"
            ],
            "staffs" => $staffs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Restaurant/Staffs/Create', [
            "app" => [
                "title" => "Add Staff"
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {

        $this->staffService->createStaff($request);
        return redirect()->back()->with('success', 'Staff Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = $this->staffService->getStaff($id);

        return Inertia::render('Restaurant/Staffs/Show', [
            "app" => [
                "title" => "View Staff"
            ],
            "staff" => $staff
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = User::findOrFail($id);

        return Inertia::render('Restaurant/Staffs/Edit', [
            "app" => [
                "title" => "Edit Staff"
            ],
            "staff" => $staff
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $this->staffService->updateStaff($request, $id);
        return redirect()->route('staffs.index')->with('success', 'Staff Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $this->staffService->removeStaff($id);

        return redirect()->route('staffs.index')->with('success', 'Staff Deleted');
    }
}
