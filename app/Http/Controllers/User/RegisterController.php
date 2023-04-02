<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Response\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequests;
use App\Http\Resources\User as UserResource;
use Spatie\Permission\Models\Role;

class RegisterController extends BaseController
{

    function __construct()
    {
         $this->middleware('permission:user-create', ['only' => ['register']]);
         $this->middleware('permission:user-list|user-create|user-edit|', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $roles = Role::pluck('name','name')->all();
        $users = User::all();
        
        if (is_null($users)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse(UserResource::collection($users, $roles), 'User retrieved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::find($id);
  
        if (is_null($users)) {
            return $this->sendError('User not found.');
        }
   
        return $this->sendResponse(new UserResource($users), 'User retrieved successfully.');
    }

   
    /**
     * Create user api
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UserRequests $request)
    {
        $input = $request->all();

        $input['password'] = bcrypt($input['password']);
        $users = User::create($input);
        
        $users->assignRole($request->input('roles'));
        $success['token'] = $users->createToken('blog-app')->plainTextToken;
        $success['name'] =  $users->name;
   
        return $this->sendResponse($success, 'User created successfully.');
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        if(is_null($user)){
            return $this->sendError('User not found.');
        }
        $input = $request->all();

        if (!empty($input['email'])) {$user->email = $input['email'];}
        if (!empty($input['password'])) {$user->email = $input['password'];}
        if (!empty($input['name'])) {$user->email = $input['name'];}
        $user->update();
        return response()->json(['message' => 'User successfully updated']);
    }

    public function delete($id)
    {
       $user = User::find($id);
        if(is_null($user)){
            return $this->sendError('User not found.');
        }
            
        $user->delete();

        return response()->json(['message' => 'User successfully deleted']);
    }
}
