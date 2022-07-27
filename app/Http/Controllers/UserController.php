<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->address = $request->input('address');
        $user->mobile_number = $request->input('mobile_number');
        if( $request->file('image')){
            $file = $request->file('image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(storage_path('images/avatar'),$filename);
            $user['image'] = $filename;
        }
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $success['name'] = $user->name;
        $success['email'] = $user->email;
        $response  = [
            'success' => true,
            'data' => $success,
            'message' => 'User Created Successfully.'
        ];
        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $user = User::where(['id'=> $id])->first();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->address = $request->input('address');
        $user->mobile_number = $request->input('mobile_number');
        if( $request->file('image')){
            $file = $request->file('image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(storage_path('images/avatar'),$filename);
            $user['image'] = $filename;
        }
        $user->save();

        $success['name'] = $user->name;
        $success['email'] = $user->email;
        $response  = [
            'success' => true,
            'data' => $success,
            'message' => 'User Updated Successfully.'
        ];
        return response()->json($response,200);
    }

    public function destroy($id)
    {
        try{
            $service = User::findOrFail($id);
            $service->delete();
            $response  = [
                'success' => true,
                'message' => 'User Deleted.'
            ];
            return response()->json($response,200);
        }catch (\Exception $e){
            $response  = [
                'success' => false,
                'message' => 'User Not Found.'. $e
            ];
            return response()->json($response,404);
        }
    }
}
