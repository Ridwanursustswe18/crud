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
                "description" => 'required|max:255'
            ]);
            $user = User::find($id);
            if (Auth::check()) {
                $blog = Blog::create([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'user_id' => $user->id
                ]);
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
    public function update(Request $request, $id)
    {

        try {
            $blog = Blog::find($id);

            if (!Gate::allows('update', $blog)) {
                abort(403);
            } else {

                $validatedData = $request->validate([
                    'title' => 'required|max:255',
                    "description" => 'required|max:255'
                ]);
                $new_blog = Blog::where('id', $id)
                    ->update([
                        'title' => $validatedData['title'],
                        'description' => $validatedData['description']
                    ]);

                //$new_blog->save();
                if ($new_blog) {
                    return response('blog updated successfully');
                }
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $blog = Blog::find($id);
            
            
            if (!Gate::allows('update', $blog)) {
                return response('You are not authorized');
            } else {
                $blog->delete();

                return response('blog deleted successfully');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}