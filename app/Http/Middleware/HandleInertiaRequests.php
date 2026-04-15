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

