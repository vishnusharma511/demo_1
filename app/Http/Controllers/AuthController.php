<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'mobile_number' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        $response  = [
            'success' => true,
            'data' => $success,
            'message' => 'User Register Successfully.'
        ];
        return response()->json($response,200);
    }

    public function login(Request $request){
        if(Auth::guard('login_guard')->attempt([
            'email'=>$request->email,
            'password'=>$request->password
        ])){
            $user = Auth::guard('login_guard')->user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $success['email'] = $user->email;
            $response  = [
                'success' => true,
                'data' => $success,
                'message' => 'User Login Successfully.',
            ];
            return response()->json($response,200);
        }else{
            $response  = [
                'success' => false,
                'message' => 'UnAuthorized User.'
            ];
            return response()->json($response,401);
        }
    }
}
