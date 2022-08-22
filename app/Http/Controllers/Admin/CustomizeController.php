<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customizeinfo;
use App\Models\CancellationPolicy;
use App\Models\Properties;
use App\Models\SliderPhoto;
use Auth;
use Image;

class CustomizeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $websiteHeaderMenu = Customizeinfo::where([['name','website-header-menu'],['user', $user->id]])->get();
        $websiteHeaderLogo = Customizeinfo::where([['name','website-header-logo'],['user', $user->id]])->first();
        $websiteHeaderFavicon = Customizeinfo::where([['name','website-header-favicony'],['user', $user->id]])->first();
        $websiteHeaderBack = Customizeinfo::where([['name','website-header-background'],['user', $user->id]])->first();

        $wesbiteFooter = Customizeinfo::where([['name','website-footer'],['user', $user->id]])->first();
        $websiteFooterMoreInfo = Customizeinfo::where([['name','website-footer-more-info'],['user', $user->id]])->get();

        $listCitiesSettings =  Customizeinfo::where([['name','website-home-city-list-settings'],['user', $user->id]])->first();
        $homePageListCities = Customizeinfo::where([['name','website-home-city-list'],['user', $user->id]])->get();

        $featuredPropertiesSettings =  Customizeinfo::where([['name','website-home-featured-properties-settings'],['user', $user->id]])->first();
        $featuredProperties = Customizeinfo::where([['name','website-home-featured-properties'],['user', $user->id]])->get();

        $websiteMeta = Customizeinfo::where([['name','website-seo'],['user', $user->id]])->first();

        $pIds = Customizeinfo::where([['name','website-home-featured-properties'],['user', $user->id]])->pluck('property')->toArray();
        $propertiesArray = $user->properties()->whereNotIn('id',$pIds)->get(['id','name']);
        $content = CancellationPolicy::where('user_id', $user->id)->first();
        $slidephotos = SliderPhoto::where('user_id', $user->id)->orderBy('order', 'ASC')->get();

        return view('admin.customize.index', compact(
            'user',
            'websiteHeaderMenu',
            'websiteHeaderLogo',
            'websiteHeaderFavicon',
            'websiteHeaderBack',
            'wesbiteFooter',
            'websiteFooterMoreInfo',
            'listCitiesSettings',
            'homePageListCities',
            'featuredPropertiesSettings',
            'featuredProperties',
            'websiteMeta',
            'propertiesArray',
            'content',
            'slidephotos'
        ));
    }

    public function saveBatch(Request $request)
    {
        $this->validate($request,[
            'website-header-background&header_title' => 'nullable|string|max:50',
            'website-header-background&header_subtitle' => 'nullable|string|max:70',
        ],[
            'website-header-background&header_title.max' => 'Header background title must not be greater than 50 characters.',
            'website-header-background&header_subtitle.max' => 'Header background subtitle must not be greater than 70 characters.'
        ]);

        $user = Auth::user();
        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        $input = $request->except('_token');
        foreach ($input as $key => $value) {
            $k = explode('&',$key)[0]; $v = count(explode('&',$key)) > 1 ? explode('&',$key)[1] : ''; $val = $value;
            $customize = Customizeinfo::where([['name',$k],['user', $user->id]])->first();

            if (count(explode('&',$key)) > 2 ) {
                $customize = Customizeinfo::find(explode('&',$key)[2]);
            }

            if ($request->hasFile($key)) {
                if ($k == 'website-header-logo') {
                    $path = 'uploads/logos';
                } elseif ($k == 'website-header-favicony') {
                    $path = 'uploads/favicons';
                } elseif ($k == 'website-home-city-list') {
                    $path = 'uploads/city-list';
                } elseif ($k == 'website-header-background') {
                    $path = 'uploads/back-images';
                }
                $file = $request->file($key);
                $destinationPath = public_path($path);
                $filename = $user->id . time() . '.' . $file->extension();
                $file->move($destinationPath, $filename);

                $val =  '/' . $path . '/' . $filename;
            }
            if ($customize) {
                $customize->update([
                    $v => $val
                ]);
            } else {
                Customizeinfo::create([
                    'name' => $k,
                    'user' => $user->id,
                    $v => $val
                ]);
            }
        }
        return back()->withFragment($hash)->with('success', __('Website settings was saved successfully!') );
    }

    public function save(Request $request)
    {
        $user = Auth::user();

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        $input = $request->except('_token');
        $data = [
            'name' => $request->name,
            'user' => $user->id,
        ];
        $k = $request->name;
        foreach ($input as $key => $value) {
            $val = $value;
            if ($request->hasFile($key)) {
                if ($k == 'website-header-logo') {
                    $path = 'uploads/logos';
                } elseif ($k == 'website-header-favicony') {
                    $path = 'uploads/favicons';
                } elseif ($k == 'website-home-city-list') {
                    $path = 'uploads/city-list';
                } elseif ($k == 'website-header-background') {
                    $path = 'uploads/back-images';
                }
                $file = $request->file($key);
                $destinationPath = public_path($path);
                $filename = $user->id . time() . '.' . $file->extension();
                $file->move($destinationPath, $filename);

                $val =  '/' . $path . '/' . $filename;
            }

            $data[$key] = $val;
        }
        if ($request->id) {
            $customize = Customizeinfo::find($request->id);
            $customize->update($data);
        } else {
            Customizeinfo::create($data);
        }

        return back()->withFragment($hash)->with('success', __('Website settings was saved successfully!') );
    }

    public function saveCancellationPolicy(Request $request)
    {
        $user = Auth::user();
        $content = CancellationPolicy::where('user_id', $user->id)->first();
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $user->id,
        ];
        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        if ($content) {
            $content->update($data);
        } else {
            $content = CancellationPolicy::create($data);
        }

        return back()->withFragment($hash)->with('success', __('Website settings was saved successfully!') );
    }

    public function saveSlides(Request $request)
    {
        $user = Auth::user();
        $slide = SliderPhoto::where('user_id', $user->id)->get();
        $slidecount = count($slide);
        $path = 'uploads/slider-photos';
        $file = $request->file('photo');
        $destinationPath = public_path($path);
        $filename = $user->id . time() . '.' . $file->extension();
        $img = Image::make($file->path())->fit(1600,800)->save($destinationPath.'/'.$filename,95);
        // $file->move($destinationPath, $filename);

        $val =  '/' . $path . '/' . $filename;
        $slide = SliderPhoto::create([
            'user_id' => $user->id,
            'photo' => $val,
            'order' => $slide->count() + 1,
        ]);
        $slide->save();
        return back()->with('success', __('Slider photo was saved successfully!') );
    }

    public function saveSubdomain(Request $request)
    {
        $user = Auth::user();

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        $this->validate($request,[
            'subdomain' => ['required', 'string', 'max:255', 'unique:users,subdomain,'.$user->id],
        ]);

        $user->update([
            'subdomain' => $request->subdomain,
            'subdomain_status' => "pending"
        ]);

        return back()->withFragment($hash)->with('success', __('Subdomain was saved successfully!') );
    }

    public function delete(Request $request)
    {
        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        $customize = Customizeinfo::find($request->id);
        $customize->delete();

        return back()->withFragment($hash)->with('success', __('Removed successfully!') );
    }

    public function deleteSlides(Request $request)
    {
        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        $sliderphoto = SliderPhoto::find($request->id);
        $sliderphoto->delete();

        return back()->withFragment($hash)->with('success', __('Removed successfully!') );
    }

    public function changeOrder(Request $request)
    {
        $item = SliderPhoto::find($request->id);

        $item->update([
            'order' => $request->order,
        ]);

        return true;
    }
}
