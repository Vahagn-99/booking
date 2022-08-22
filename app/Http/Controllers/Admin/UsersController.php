<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Itstructure\GridView\DataProviders\EloquentDataProvider;

use App\Models\Agencyask;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $dataProvider = new EloquentDataProvider(User::query());

        return view('admin.users.index', compact('dataProvider'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function save(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $this->validate($request,[
            'subdomain' => ['nullable', 'string', 'max:255', 'unique:users,subdomain,'.$user->id],
        ]);

        $user->update([
            'subdomain' => $request->subdomain,
            'subdomain_status' => $request->subdomain_status
        ]);

        return back()->with('success', __('Subdomain was saved successfully!') );
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();

        return back()->with('success', __('Removed successfully!') );
    }

    public function notifications() {
        $agencyask  = Agencyask::get();

        return view('admin.notifications.index', compact('agencyask'));
    }
}
