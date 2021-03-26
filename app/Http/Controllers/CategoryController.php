<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriesCollection;
use App\Models\Category;
use App\Utils\Transformer;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Search tags by name.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $payload = $request->validate([
            'q' => 'nullable|string'
        ]);

        try {
            $categories = Category::where('name', 'like', '%' . $payload['q'] . '%')->paginate(10);

            return (new CategoriesCollection($categories))
                        ->additional(Transformer::skeleton(true, 'Success to load search categories data.', null, true));
        } catch (\Throwable $th) {
            return Transformer::failed('Success to load search categories data.');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
