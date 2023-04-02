<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController as BaseController;
use App\Models\Article;
use App\Http\Requests\ArticleRequests;
use App\Http\Resources\Article as ArticleResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Log;

class ArticleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:article-list|article-create|article-edit|', ['only' => ['index','store']]);
         $this->middleware('permission:article-create', ['only' => ['create','store']]);
         $this->middleware('permission:article-edit', ['only' => ['edit','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // get role name
        $user_role = $user->roles->pluck('name')->take(1)[0];
               
        if($user_role == 'user'){
            $articles = Article::where('user_id',$user->id)->get();
        } else {
            $articles = Article::all();
        }
        return $this->sendResponse(ArticleResource::collection($articles), 'Article retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ArticleRequests $request)
    {
        $user_id = Auth::user()->id;
        $input = $request->all();
        $input['user_id'] = $user_id;
        $article = Article::create($input);
   
        return $this->sendResponse(new ArticleResource($article), 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
  
        if (is_null($article)) {
            return $this->sendError('Article not found.');
        }
   
        return $this->sendResponse(new ArticleResource($article), 'Article retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update($id, ArticleRequests $request)
    {
        $user = Auth::user();
        // get role name
        $user_role = $user->roles->pluck('name')->take(1)[0];

        $article = Article::find($id);

        //recheck ownership article when role is user
        if( $user_role == 'user' && $article->user_id != $user->id){
            return $this->sendError('This Article was belong to someone.');
        }

        $input = $request->all();

        $article->title     = $input['title'];
        $article->author    = $input['author'];
        $article->detail    = $input['detail'];
        $article->update();
   
        return $this->sendResponse(new ArticleResource($article), 'Article update successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        // get role name
        $user_role = $user->roles->pluck('name')->take(1)[0];

        $article = Article::find($id);

        //recheck ownership article when role is user
        if( $user_role == 'user' && $article->user_id != $user->id){
            return $this->sendError('This Article was belong to someone.');
        }

        $article->delete();

        return $this->sendResponse([], 'Article deleted successfully.');
    }
}
