<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blogs = Blog::paginate(5);
        return view('blogs.index', ['blogs' => $blogs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();
  
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }

        $blog = new Blog;
        $blog->title = $request->title;
        $blog->slug = Str::of($request->title)->slug('-');
        $blog->description = $request->description;
        $blog->image = $input['image'];
        $blog->created_by = \Auth::id();
        $blog->created_at  = date("Y-m-d H:i:s");
        $blog->updated_at  = date("Y-m-d H:i:s");
        $blog->save();

        return redirect()->route('blogs.index')->with('success','Blog has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return view('blogs.show',compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit',compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $input = $request->all();
  
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }else{
            unset($input['image']);
        }

        $blog->update($input);

        return redirect()->route('blogs.index')->with('success','Blog has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blogs.index')->with('success','Blog has been deleted successfully');
    }

    public function uploadImage(Request $request) {    
        if($request->hasFile('upload')) {
                  $originName = $request->file('upload')->getClientOriginalName();
                  $fileName = pathinfo($originName, PATHINFO_FILENAME);
                  $extension = $request->file('upload')->getClientOriginalExtension();
                  $fileName = $fileName.'_'.time().'.'.$extension;
               
                  $request->file('upload')->move(public_path('images'), $fileName);
          
                  $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                  $url = asset('images/'.$fileName); 
                  $msg = 'Image uploaded successfully'; 
                  $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                      
                  @header('Content-type: text/html; charset=utf-8'); 
                  echo $response;
        }
     } 
}
