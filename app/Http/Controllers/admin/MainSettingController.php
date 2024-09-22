<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class MainSettingController extends Controller
{
    public function getAboutUs(){
        return view('pages.admin.main_setting.about_us');
    }
}
