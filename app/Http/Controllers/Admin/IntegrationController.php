<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Itstructure\GridView\DataProviders\EloquentDataProvider;
use Auth;
use App\Repositories\ChannexRepositoryInterface;
use App\Models\Channexgroups;
use App\Models\Channexproperties;
use App\Models\Channexrooms;
use App\Models\Channexrates;
use App\Models\Channexroomidens;
use App\Models\Properties;
use App\Models\Currency;

class IntegrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ChannexRepositoryInterface $channexRepository)
    {
        $this->currencies = Currency::getCurrencies();
        $this->channexRepository = $channexRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $dataProvider = new EloquentDataProvider(Channexproperties::query());

        return view('admin.integration.index', compact('user', 'dataProvider'));
    }

    public function settings($id=null)
    {
        $title = 'Add new group';
        $group = null;
        $properties = Properties::get(['id','name', 'owner'])->sortBy([['owner', 'asc'], ['name', 'asc']])->filter(function($item) {
            return !$item->channex_room;
        });
        if ($id) {
            $group = Channexproperties::find($id);
            $title = __('Edit group ') . $group->name;
//            $properties = Properties::all();
            $properties = Properties::get(['id','name'])->filter(function($item) use ($group) {
                return !$item->channex_room || ($item->channex_room && $item->channex_room->channexproperty_iden === $group->id);
            });
        }

        return view('admin.integration.settings', compact('title', 'group', 'properties'));
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255']
        ]);

        try {
            $this->channexRepository->save($request);
        } catch (\Exception $e) {
            \Log::info('Saving Channex group failed: ' . $e->getTraceAsString());
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin/integration')->with('success', __('Group settings was saved successfully!') );
    }

    public function delete(Request $request)
    {
        $chp = Channexproperties::findOrFail($request->id);
        if ($chp->rooms()->count() > 0) {
            foreach ($chp->rooms()->get() as $chr) {
                foreach ($chr->reservations()->get() as $r) {
                    $r->delete();
                }
                foreach ($chr->rate_plans()->get() as $chrp) {
                    try {
                        $this->channexRepository->deleteRatePlan($chrp->channel_iden);
                    } catch (\Exception $e) {\Log::info('rate plan delete '.$e->getMessage());}
                    $chrp->delete();
                }
                try {
                    $this->channexRepository->deleteRoomType($chr->channel_iden);
                } catch (\Exception $e) {\Log::info('room delete '.$e->getMessage());}
                $chr->delete();
            }
        }
        try {
            $this->channexRepository->deleteProperty($chp->channel_iden);
        } catch (\Exception $e) {}
        $chp->delete();

        return back()->with('success', __('Removed successfully!') );
    }
}
