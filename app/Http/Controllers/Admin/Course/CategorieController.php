<?php

namespace App\Http\Controllers\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Categorie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Course\Categorie\CategorieResource;
use App\Http\Resources\Course\Categorie\CategorieCollection;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $state = $request->state;
        $categories = Categorie::filterAdvance($search,$state)->orderby("id", "desc")->get();

        return response()->json([
            "categories" => CategorieCollection::make($categories),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile("portada")) {
            $path = Storage::putFile("categories", $request->file("portada"));
            $request->request->add(["imagen" => $path]);
        }
        $categorie = Categorie::create($request->all());
        return response()->json(["categorie" =>CategorieResource::make($categorie)]);
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
        $categorie = Categorie::findOrFail($id);
        if ($request->hasFile("portada")) {
            $path = Storage::putFile("categories", $request->file("portada"));
            $request->request->add(["imagen" => $path]);
        }
        if ($request->password) {
            $request->request->add(["password" => bcrypt($request->password)]);
        }
        $categorie->update($request->all());
        return response()->json(["categorie" => CategorieResource::make($categorie)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();
        return response()->json(["message" => 200]);
    }
}
