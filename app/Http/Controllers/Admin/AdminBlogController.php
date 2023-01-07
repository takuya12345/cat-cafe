<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreBlogRequest;

class AdminBlogController extends Controller
{
    // ブログ一覧画面
    public function index()
    {
        $blogs = Blog::all();
        return view('admin.blogs.index', ['blogs' => $blogs]);
    }

    //　ブログ投稿画面
    public function create()
    {
        return view('admin.blogs.create');
    }

    // ブログ投稿処理
    public function store(StoreBlogRequest $request)
    {
        // $saveImagePath = $request->file('image')->store('blogs', 'public');
        // $blog = new Blog($request->validated());
        // $blog->image = $saveImagePath;
        // $blog->save();

        // createメソッドの場合
        $validated = $request->validated();
        $validated['image'] = $request->file('image')->store('blogs', 'public');
        Blog::create($validated);

        return to_route('admin.blogs.index')->with('success', 'ブログ投稿しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', ['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
