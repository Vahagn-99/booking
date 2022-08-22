<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Itstructure\GridView\DataProviders\EloquentDataProvider;
use Illuminate\Support\Facades\Hash;
use Auth;

class DashboardController extends Controller
{
    public function profileSettings()
    {
        $user = Auth::user();

        return view('admin.profile.index', compact('user'));
    }

    public function changePassword() {
        $user = Auth::user();

        return view('admin.profile.change-password', compact('user'));
    }

    public function saveProfile(Request $request)
    {
        $user = Auth::user();

        $this->validate($request,[
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'subdomain' => ['nullable', 'string', 'max:255', 'unique:users,subdomain,'.$user->id],
        ]);

        if ($request->work_schedule_type == 'same') {
            $request->request->add(['tue_work_time' => $request->mon_work_time]);
            $request->request->add(['wed_work_time' => $request->mon_work_time]);
            $request->request->add(['thu_work_time' => $request->mon_work_time]);
            $request->request->add(['fri_work_time' => $request->mon_work_time]);
        }

        $input = $request->all();
        $user->fill($input)->save();

        return back()->with('success', __('Profile settings was saved successfully!') );
    }

    public function savePassword(Request $request)
    {
        $user = Auth::user();

        $this->validate($request,[
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', __('Password was updated successfully!') );
    }
}
