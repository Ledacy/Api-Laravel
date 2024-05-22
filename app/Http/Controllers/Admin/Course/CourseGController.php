<?php

namespace App\Http\Controllers\Admin\Course;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Models\Course\Categorie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Course\CourseGResource;
use App\Http\Resources\Course\CourseGCollection;

class CourseGController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $state = $request->state;
        // filterAdvance($search, $state)->
        $courses = Course::orderBy("id", "desc")->get();

        return response()->json([
            "courses" => CourseGCollection::make($courses),
        ]);
    }
    public function config()
    {
        $categories = Categorie::where("categorie_id", Null)->orderBy("id", "desc")->get();
        $subcategorie = Categorie::where("categorie_id", "<>", Null)->orderBy("id", "desc")->get();
        $instrutores = User::where("is_instructor", 1)->orderBy("id", "desc")->get();
        return response()->json([
            "categories" => $categories,
            "subcategories" => $subcategorie,
            "instructores" => $instrutores->map(function($user){
                return [
                    "id" => $user->id,
                    "full_name" => $user->name .''. $user->surname,
                ];
            }),
        ]);
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
    public function store(Request $request)
    {
        $is_exits = Course::where("title",$request->title)->first();
        if($is_exits){
            return response()->json(["message" => 403,"message_text" => "YA EXISTE UN CURSO CON ESTE TITULO"]);
        }
        if($request->hasFile("portada")){
            $path = Storage::putFile("courses",$request->file("portada"));
            $request->request->add(["imagen" => $path]);
        }
        $request->request->add(["slug" => Str::slug($request->title)]);
        $request->request->add(["requirements" => json_encode(explode(",",$request->requirements))]);
        $request->request->add(["who_is_it_for" => json_encode(explode(",",$request->who_is_it_for))]);
        $course = Course::create($request->all());
        // "course" => CourseGResource::make($course)
        return response()->json(["message" => 200]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);
        if ($request->hasFile("portada")) {
            $path = Storage::putFile("courses", $request->file("portada"));
            $request->request->add(["imagen" => $path]);
        }
        $request->request->add(["slug" => Str::slug($request->title)]);
        $request->request->add(["requirements" => json_encode(explode(",",$request->requirements))]);
        $request->request->add(["who_is_it_for" => json_encode(explode(",",$request->who_is_it_for))]);
        $course->update($request->all());
        return response()->json(["course" => CourseGResource::make($course)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(["message" => 200]);
    }
}
