<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'auth' => [
                'user' => function () {
                    $user = auth()->user();

                    if (!$user) {
                        return null;
                    }

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar ? $user->avatar : null,
                        'role' => $user->getRoleNames()->first(),
                        'restaurant_id' => $user->restaurant_id,
                        'permissions' => $user->getAllPermissions()->pluck('name')
                    ];
                },
            ],

            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],

            'notifications' => function () use ($request) {
                if (!$request->user())
                    return [];

                return $request->user()
                    ->notifications()
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(fn($n) => [
                        'id' => $n->id,
                        'title' => $n->data['title'] ?? '',
                        'message' => $n->data['message'] ?? '',
                        'read_at' => $n->read_at,
                    ]);
            },

            'unread_count' => function () use ($request) {
                return $request->user()
                    ? $request->user()->unreadNotifications()->count()
                    : 0;
            },

            'restaurant' => function () use ($request) {
                $user = $request->user();

                if (!$user || !$user->restaurant) {
                    return null;
                }

                return [
                    'id' => $user->restaurant->id,
                    'name' => $user->restaurant->name,
                    'address' => $user->restaurant->address,
                    'phone' => $user->restaurant->phone,
                    'email' => $user->restaurant->email,
                    'logo' => $user->restaurant->logo ? $user->restaurant->logo : null,
                ];
            },
        ];
    }
}

