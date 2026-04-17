<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\Order\CreateRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Order;
use App\Models\Table;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
        $this->middleware('permission:create-order', ['only' => ['create', 'store']]);
        $this->middleware('permission:view-orders', ['only' => ['index', 'show']]);
        $this->middleware('permission:update-order', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-order', ['only' => ['destroy']]);
        $this->middleware('permission:search-order', ['only' => ['search']]);


    }

    public function index()
    {
        $orders = $this->orderService->getRecentOrders();


        return Inertia::render('Restaurant/Orders/Index', [
            'app' => ['title' => 'Orders'],
            'orders' => $orders,
            'order_statuses' => OrderStatus::values()
        ]);
    }

    public function create()
    {
        $data = $this->orderService->getDataForCreate();

        return Inertia::render('Restaurant/Orders/Create', [
            'app' => ['title' => 'Create Order'],
            'tables' => $data['tables'],
            'categories' => $data['categories'],
            'staffs' => $data['staffs']
        ]);
    }

    public function store(CreateRequest $request)
    {
        $this->orderService->createOrder($request->validated());

        return redirect()->back()
            ->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        $data = $this->orderService->getDataForEdit($order);

        return Inertia::render('Restaurant/Orders/Edit', [
            'app' => ['title' => 'Edit Order #' . $order->id],
            'order' => $data['order'],
            'tables' => $data['tables'],
            'categories' => $data['categories'],
            'order_statuses' => $data['order_statuses'],
        ]);
    }

    public function update(UpdateRequest $request, Order $order)
    {
        try {
            $this->orderService->updateOrder($order, $request->validated());

            return redirect()->back()
                ->with('success', 'Order updated successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function searchOrder(Request $request)
    {
        $orders = $this->orderService->searchOrders(
            $request->table,
            $request->searchTerm,
            $request->status
        );

        return Inertia::render('Restaurant/Orders/Search', [
            'app' => ['title' => 'Search Order'],
            'tables' => Table::all(),
            'orders' => $orders,
            'filters' => $request->only('table', 'searchTerm', 'status'),
            'statuses' => OrderStatus::values(),
        ]);
    }
}