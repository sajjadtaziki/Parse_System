<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserReques;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserShowResource;
use App\Http\Resources\User\UserUpdateResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

//use http\Client\Curl\User;


class UserController extends Controller
{
//Storm
    public function Storm(UserReques $userRequest)
    {
        $user=User::create($userRequest->all());
        if ($userRequest->hasFile('picture'))
        {
            $pictureUrl=Storage::putFile('/User',$userRequest->picture);
            $user->update([
                'Url_Picture'=>$pictureUrl
            ]);
        }
        return response()->json([
            "message" => "کاربر با موفقیت ثبت شد",
            "deta" => new UserShowResource($user)
        ],200);
    }

//Show
    public function Show($id)
    {

        $User = User::find($id);
        if ($User == null){
            return response()->json([
                "message"=>"کاربر یافت نشد",
            ],403);
        }
        else{
            return response()->json([
                "message"=>"کاربر مورد نظر",
                "deta"=> new UserShowResource($User)
            ],200);
        }
}
//ShowAll
    public function ShowAll()
    {
        $Users = DB::table('Users')->simplePaginate(1);
        if ($Users==null)
        {
            return response()->json([
                "message"=>"متاسفانه کاربری موجود نیست",
            ],403);
        }
        else{
            return response()->json([
                "massage"=>"لیست کاربران",
                "deta"=>UserShowResource::collection($Users)
            ]);
        }
    }

    public function update(UserUpdateRequest $userUpdateRequest , User $user)
    {
        $user->update($userUpdateRequest->all());

        return response()->json([
            "message"=>"اطلاعات به روز رسانی شود",
            "deta"=>new UserUpdateResource($user)
        ],200);
    }

    public function delete($id)
    {
        $User = User::find($id);
        if ($User==null)
        {
            return response()->json([
                "message"=>"کاربری وجود ندارد"
            ]);
        }
        else
        {
            $User->delete();
            return response()->json([
                "massage"=>"کاربر حذف شد"
            ]);
        }

    }

}
