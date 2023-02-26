<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Exception;
use Hamcrest\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class BlogController extends Controller
{
    //
    public function create(Request $request)
    {

        try {
            $id = $request->user()->id;


            $validatedData = $request->validate([
                'title' => 'required|max:255',
                "description" => 'required|max:255',
                'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:5000'

            ]);

            if (Auth::check()) {
                $blog = Blog::create([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'user_id' => $id
                ]);
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $path = $image->store('images', 'public');
                    $blog->image = $path;
                    $blog->save();
                }
                $blog->save();
            } else {
                return response("You must be logged in", 403);
            }
            if ($blog) {
                return response('blog created successfully');
            } else {
                return response('try again', 401);
            }


        } catch (Exception $e) {
            echo ($e->getMessage());
        }
    }
    public function edit(Blog $blog)
    {

        return view('blog.edit', ['blog' => $blog]);
    }
    public function update(Request $request, Blog $blog)
    {

        try {

            $id = $blog->id;
            //dd($id);
            //$this->authorize('update', $blog);

            $validatedData = $request->validate([
                'title' => 'nullable|max:255',
                "description" => 'nullable|max:255',
                'image' => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5000'
            ]);
            //dd($request->all());
            $blog->update([
                'title' => isset($validatedData['title']) ? $validatedData['title'] : $blog->title,
                'description' => isset($validatedData['description']) ?  $validatedData['description'] : $blog->description
            ]);
            
            if ($request->hasFile('image') && $validatedData['image']) {

                $image = $request->file('image');
                $path = $image->store('images', 'public');
                $blog->image = $path;
                $blog->save();
            }



            // if ($new_blog) {
            //     return response("updated successfully");
            // }
            return redirect()->route('blogs.show')->with('success', "Blog is successsfully updated");
        } catch (Exception $e) {
            echo ($e->getMessage());
        }
    }
    public function destroy(Blog $blog)
    {
        try {
            $id = $blog->id;
            $blog = Blog::find($id);
            $this->authorize('delete', $blog);

            $blog->delete();

            return response('blog deleted successfully');

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function show()
    {
        $blogs = Blog::get();
        echo ($blogs);
    }
}