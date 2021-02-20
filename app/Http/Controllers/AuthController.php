<?php

namespace App\Http\Controllers;

use App\Base;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\TitleOnlyResource;
use App\Models\Announcement;
use App\Models\Period;
use App\Models\Role;
use App\Models\User;
use App\Models\YearLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function rolesIndex()
    {
        return response()->json([
            'message' => 'Roles GET success',
            'data' => TitleOnlyResource::collection(Role::all())
        ]);
    }

    public function yearLevelsIndex()
    {
        return response()->json([
            'message' => 'Year Levels GET success',
            'data' => TitleOnlyResource::collection(YearLevel::all())
        ]);
    }

    public function periodsIndex()
    {
        return response()->json([
            'message' => 'Periods GET success',
            'data' => TitleOnlyResource::collection(Period::all())
        ]);
    }

    public function announcement(){
        return response()->json([
            'message' => "Announcement get successfully!",
            'data' =>AnnouncementResource::collection(Announcement::limit(Base::ANNOUNCEMENT_LIMIT)->get()->sortByDesc('created_at'))
        ]);
    }

    public function changePassword()
    {
        return view('changePassword');
    }

    public function updatePassword(Request $request){
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ]);
        auth()->user()->update(['password' => Hash::make($validated['password'])]);

        return response()->json([
            'message' => "Password has been changed successfully!"
        ]);
    }


}
