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

class AuthorizeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'status']);
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

    public function announcement(Request $request)
    {
        $data = AnnouncementResource::collection(Announcement::orderBy("id", "desc")->paginate($request->get('rowsPerPage')));
        return response()->json([
            'message' => "Announcement get successfully!",
            'data' => $data
        ]);
    }

    public function changePassword()
    {
        if (auth()->user()->role_id == Base::STUDENT_ROLE_ID) {
            abort(404);
        }
        return view('changePassword');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ]);
        auth()->user()->update(['password' => Hash::make($validated['password'])]);

        return response()->json([
            'message' => "Password has been changed successfully!"
        ]);
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);
        $user->update(['password' => Hash::make($validated['password'])]);
        return response()->json(["Success"]);
    }
}
