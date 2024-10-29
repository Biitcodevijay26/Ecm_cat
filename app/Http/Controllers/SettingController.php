<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cms;
use App\Models\Company;
use App\Models\IconSetting;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function showLogo(Request $request)
    {
        $dataOne = Setting::where('key', 'logo')->first();
        $data = [
            'title'   => 'Home',
            'heading' => 'Manage Logo',
            'data'    => $dataOne,
        ];
        return view('setting.logo.logo', $data);
    }

    public function saveLogo(Request $request)
    {
        if ($request->file('file')) {
            $image       = $request->file('file');
            $logoImage = 'logo.png';
            $image->move(public_path('uploads/logo/'),$logoImage);

            if(isset($logoImage) && $logoImage){
                $record = Setting::where('key', 'logo')->first();
                $imageOld = $record->value;
                $record->value = $logoImage;

                if ($record->save()) {
                    return response()->json(['status' => 'true','data' => $logoImage]);
                } else {
                    return response()->json(['status' => 'false']);
                }
            }
        } else {
            return response()->json(['status' => 'false']);
        }

    }

    // Icon Settings
    function showIconSettings(Request $request){
        $company_list = Company::where('status',1)->get();

        $data = [
            'title'       => 'Home',
            'heading'     => 'Uploads Icons',
            'getCompany'  => $company_list,
        ];
        return view('setting.icon_setting.icons',$data);
    }

    public function saveIconSetting(Request $request)
    {
        $originalImages = ['charging-off.svg','tower-off.svg','unit-off.svg','acsolar-xl-off-icon.svg',
            'load-xl-off-icon.svg','dcsolar-xl-off-icon.svg','battery_full_icon_off.svg','solar-xl-off-icon.svg',
            'charging.svg','tower.svg','unit.svg','acsolar.svg','load.svg','dcsolar.svg','battery_full_icon.svg','solar.svg'
        ];
        if($request->has('icon_id') && $request->icon_id && $request->has('icon_name') && $request->icon_name && $request->has('company_id') && $request->company_id){
            if ($request->file('file')) {
                $image        = $request->file('file');
                $clean_string = $this->cleanString($request->icon_name);
                $logoImage    = $request->company_id.'-'.time().'-'.$clean_string.'.svg';
                $image->move(public_path('theme-asset/images/overview-icons/'),$logoImage);
                if(isset($logoImage) && $logoImage){
                    $mytime   = Carbon::now();
                    $now_time = $mytime->toDateTimeString();
                    $record   = IconSetting::where('_id', $request->icon_id)->first();
                    if($record->icon_name){
                        if (!in_array($record->icon_name, $originalImages)) {
                            $path=public_path().'/theme-asset/images/overview-icons/'.$record->icon_name;
                            if (file_exists($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $record->icon_name  = $logoImage;
                    $record->updated_at = $now_time;
                    if ($record->save()) {
                        return response()->json(['status' => 'true','data' => $logoImage]);
                    } else {
                        return response()->json(['status' => 'false']);
                    }
                }
            } else {
                return response()->json(['status' => 'false']);
            }
        } else {
            return response()->json(['status' => 'false']);
        }

    }

    public function getIcons(Request $request){
        if($request->has('company_id') && $request->company_id){
            $dataOne = IconSetting::where('company_id',$request->company_id)->get();
            if($dataOne && count($dataOne) > 0){
                return response()->json(['status' => 'true', 'data' => $dataOne]);
            } else {
                // When not found default icon then create
                generateIconSettings($request->company_id);
                $dataOne = IconSetting::where('company_id',$request->company_id)->get();
                return response()->json(['status' => 'true', 'data' => $dataOne]);
            }
        }
    }

    function cleanString($string){
        if($string){
            $string = str_replace(' ', '-', $string);
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
            $string = preg_replace('/-+/', '-', $string);
            $string = strtolower($string);
            return $string;
        }
    }
}
