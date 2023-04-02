<?php
   
namespace App\Http\Controllers\User;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Response\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Http\Requests\UserRequests;
use App\Http\Resources\User as UserResource;
use Spatie\Permission\Models\Role;
   
class AuthController extends BaseController
{

    public function signin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('blog-app')->plainTextToken; 
            $success['name'] =  $authUser->name;
            $success['token-type'] =  "Bearer";
   
            return $this->sendResponse($success, 'User signed in');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        // Assign Role
        if(!empty($input['role'])){
            if($input['role']=='admin'){
                $user->assignRole('admin');
            }
            if($input['role']=='manager'){
                $user->assignRole('manager');
            } else {
                $user->assignRole('user');
            }
        } else {
            $user->assignRole('user');
            $input['role'] = 'user';

        }

        $success['token'] =  $user->createToken('blog-app')->plainTextToken;
        $success['name'] =  $user->name;
        $success['token-type'] =  "Bearer";
        $success['role'] =  $input['role'];
   
   
        return $this->sendResponse($success, 'User created successfully.');
    }

    public function logout(Request $request)
    {
        if($request->user()){
            auth()->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'User successfully signed out']);
        }else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
   
}