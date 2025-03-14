<?php

namespace App\Actions\Models\User;

use App\Models\User;

class StandardActions
{
    public static function index($request)
    {
        if (!isset($request))
        {
            return User::paginate();
        }

        $users = User::query();

        if (isset($request['filters']) && !empty($request['filters']))
        {
            $filters = $request['filters'];

            $users->query()
                ->when(isset($filters['name']), function($subquery) use ($filters) { $subquery->where('name', $filters['name']); })
                ->when(isset($filters['email']), function($subquery) use ($filters) { $subquery->where('email', $filters['email']); })
                ->when(isset($filters['phone_no']), function($subquery) use ($filters) { $subquery->where('name', $filters['phone_no']); })
                ;
        }

        if (isset($request['search']))
        {
            $search = $request['search'];

            $users->query()
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone_no', 'like', "%{$search}%")
                ;
        }

        return $users->paginate();
    }

    public static function show($id)
    {
        $user = User::findOrFail($id);

        return $user;
    }

    public static function store($request)
    {
        $user = User::create($request);

        return $user;
    }

    public static function update($id, $request)
    {
        $user = User::findOrFail($id);
        $user = $user->update($request);

        return $user;
    }

    public static function delete($id)
    {
        $user = User::findOrFail($id);
        $user = $user->delete();

        return $user;
    }
}
