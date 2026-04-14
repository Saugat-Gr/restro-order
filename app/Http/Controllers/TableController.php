<?php

namespace App\Http\Controllers;

use App\Enums\TableStatus;
use App\Http\Requests\Table\CreateRequest;
use App\Http\Requests\Table\UpdateRequest;
use App\Models\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $tableNumber = $request->input('table_number');

        $query =  $this->filterData($status, $tableNumber);

        $tables = $query->paginate(10)->withQueryString();


        $statuses = TableStatus::values();

        return Inertia::render('Restaurant/Table/Index', [
            "app" => [
                "title" => "Tables"
            ],
            "tables" => $tables,
            "statuses" => $statuses,
            "filters" => $request->only(['status', 'table_number'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tableStatuses = TableStatus::cases();

        return Inertia::render('Restaurant/Table/Create', [
            "app" => [
                "title" => "Create Table"
            ],
            'tableStatuses' => $tableStatuses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $validated_data = $request->validated();

        $validated_data['restaurant_id'] = auth()->user()->restaurant_id;

        $table = Table::create($validated_data);

        Log::info($table);

        return redirect()->route('tables.index')->with('success', 'Table Created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        $tableStatuses = TableStatus::cases();

        return Inertia::render('Restaurant/Table/Edit', [
            "app" => [
                "title" => 'Edit Table'
            ],
            'table' => $table,
            'tableStatuses' => $tableStatuses
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Table $table)
    {
        $validated_data = $request->validated();

        $table->update($validated_data);

        return redirect()->route('tables.index')->with('success', 'Table Updated.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->back()->with('success', 'Table Deleted.');
    }

    private function filterData($status, $table_number){
    
        $query = Table::query();

        return $query->when($status, function(Builder $q) use ($status){
           
            $q->where('status', $status);
        
        })->when($table_number, function(Builder $q) use ($table_number){
             $q->where('table_number', 'LIKE' , '%' . $table_number . "%");
        });


    }
}
