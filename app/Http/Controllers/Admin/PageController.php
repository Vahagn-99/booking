<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Itstructure\GridView\DataProviders\EloquentDataProvider;
use Auth;
use App\Models\Pages;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $subdomain = null;
        if (Auth::user()->subdomain && Auth::user()->subdomain_status == 'active') {
            $subdomain = Auth::user()->subdomain;
        }
        $dataProvider = new EloquentDataProvider(Pages::query()->where('user_id', Auth::user()->id));
        return view('admin.pages.index', compact('dataProvider','subdomain'));
    }

    public function create()
    {
        $page = null;
        return view('admin.pages.create', compact('page'));
    }

    public function edit($id)
    {
        $page = Pages::where([['user_id', Auth::user()->id], ['id',$id]])->first();
        if (!$page) {
            abort(404);
        }
        return view('admin.pages.create', compact('page'));
    }

    public function save(Request $request)
    {
        $user = Auth::user();
        if ($request->id) {
            $page = Pages::find($request->id);
            $page->update([
                'title' => $request->title,
                'title_fr'  => $request->title_fr,
                'content' => $request->content,
                'content_fr' => $request->content_fr,
                'slug' => Str::slug($request->title, "-"),
            ]);
        } else {
            Pages::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'title_fr'  => $request->title_fr,
                'content' => $request->content,
                'content_fr' => $request->content_fr,
                'slug' => Str::slug($request->title, "-"),
            ]);
        }

        return redirect()->route('admin/pages')->with('success', __('page was saved successfully!') );
    }

    public function delete(Request $request)
    {
        $page = Pages::find($request->id);
        $page->delete();

        return back()->with('success', __('Removed successfully!') );
    }
}
