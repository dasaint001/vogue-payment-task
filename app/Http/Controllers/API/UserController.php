<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller 
{
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
    */ 

    public function userLogin(){ 

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
          
            return response()->json([
                'status' => true,
                'message' => 'logged in'
            ], 200); 
        } else{  
            return response()->json([
                'status' => false,
                'message' => 'Unauthorised'
            ], 401);
        } 
    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function registerUser(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'confirm_password' => 'required|same:password', 
        ]);

        if ($validator->fails()) { 
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 401);            
        }

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input); 

        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->name;

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $success['token']
        ];

        return response()->json([
            'status' => true,
            'message' => 'Registeration successful',
            'data' => $data
        ], 200);
       
    }

    public function getUser(Request $request, $id) 
    { 
        $user = User::where('id', $id)->first();

        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'User no found'
            ], 404); 
        }

        return response()->json([
            'status' => true,
            'message' => $user
        ], 200);
    }

    public function getAllUsers(Request $request) 
    { 
        $users = User::all();

        return response()->json([
            'status' => true,
            'message' => $users
        ], 200);
    }

}