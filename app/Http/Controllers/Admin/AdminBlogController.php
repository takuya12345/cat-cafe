<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cat;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;

class AdminBlogController extends Controller
{
    // ブログ一覧画面
    public function index()
    {
        $blogs = Blog::latest('updated_at')->paginate(10);
        // 毎回記載することになるので、bladeファイルに namespace から直接記載
        // $user = Auth::user();
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
    public function edit(Blog $blog)
    {
        $categories = Category::all();
        $cats = Cat::all();

        // 毎回記載することになるので、bladeファイルに namespace から直接記載
        // $user = Auth::user();
        return view('admin.blogs.edit', ['blog' => $blog, 'categories' => $categories, 'cats' => $cats]);
    }

    // 指定したIDのブログの編集画面
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $updateData = $request->validated();

        // 画像を変更する場合
        if ($request->has('image')) {
            // 変更前の画像削除
            Storage::disk('public')->delete($blog->image);
            // 変更後の画像をアップロード、保存パスを更新対象データにセット
            $updateData['image'] = $request->file('image')->store('blogs', 'public');
        }
        $blog->category()->associate($updateData['category_id']);
        $blog->cats()->sync($updateData['cats'] ?? []);
        $blog->update($updateData);

        return to_route('admin.blogs.index')->with('success', 'ブログを更新しました');
    }

    // 指定したIDのブログの削除処理
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        Storage::disk('public')->delete('$blog->image');

        return to_route('admin.blogs.index')->with('success', 'ブログを削除しました');
    }
}
