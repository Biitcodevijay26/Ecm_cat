<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cms;

class CmsController extends Controller
{
    public function page($page='')
    {
       	$page = str_replace('-', '_', $page);
        $dataOne = Cms::where('key', $page)->first();

        $page = str_replace('_', ' ', $page);
        $data = [
            'title' => 'Home',
            'module' => ucwords($page),
            'heading' => 'Edit '.ucwords($page),
            'data' => $dataOne,            
           
        ];

        return view('cms/edit_cms_page', $data);
    }

    public function save(Request $request)
    {
        
        $Cms = Cms::find($request->id);                
        $Cms->value = $request->value;       

        if ($Cms->save()) {
            $response['message'] = 'Data saved successfully';
            $response['success'] = true;
        } else {
            $response['message'] = 'Not able to save data.';
            $response['success'] = false;
        }
        return response()->json($response);

    }
}
