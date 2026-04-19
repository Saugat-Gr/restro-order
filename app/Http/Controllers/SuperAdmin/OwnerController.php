<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Owner\UpdateRequest;
use App\Services\SuperAdmin\OwnerService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OwnerController extends Controller
{

    public function __construct(protected OwnerService $ownerService)
    {
        $this->middleware('permission:view-owners', ['only' => ['index']]);
        $this->middleware('permission:create-owner', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-owner', ['only' => ['edit', 'update']]);
        $this->middleware('permission:remove-owner', ['only' => ['destroy']]);


    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $owners = $this->ownerService->getOwners($request);
        return Inertia::render('SuperAdmin/Owners/Index', [
            "app" => [
                "title" => 'Owners'
            ],
            "owners" => $owners
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unassigned_owners = $this->ownerService->getUnassignedOwners();

        return Inertia::render('SuperAdmin/Owners/Create', [
            "app" => [
                "title" => "Create Owner"
            ],
            'unassigned_owners' => $unassigned_owners
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {

        $this->ownerService->updateOwnerData($request, $id);

        return redirect()->back()->with('success', 'User Status Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {


        if ($this->ownerService->removeOwner($id)) {

            return redirect()->back()->with('success', 'Owner Removed');
        }
        return back()->with('error', 'Restaurant Exists for the User');

    }
}
