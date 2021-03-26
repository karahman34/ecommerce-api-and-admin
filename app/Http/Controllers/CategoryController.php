<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Helpers\ExcelHelper;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ExportRequest;
use App\Http\Requests\ImportRequest;
use App\Http\Resources\CategoriesCollection;
use App\Http\Resources\CategoryResource;
use App\Imports\CategoriesImport;
use App\Models\Category;
use App\Utils\Transformer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

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
        try {
            if (!request()->wantsJson()) {
                return view('categories', [
                    'title' => 'Categories'
                ]);
            }

            return DataTables::of(Category::query())
                                ->addColumn('actions', function (Category $category) {
                                    return view('components.datatables.actions-button', [
                                        'item' => $category,
                                        'item_title' => $category->name,
                                        'datatable' => '#dt-categories',
                                        'modal' => '#category-form-modal',
                                        'edit_url' => route('categories.edit', ['category' => $category->id]),
                                        'delete_url' => route('categories.destroy', ['category' => $category->id]),
                                    ]);
                                })
                                ->rawColumns(['actions'])
                                ->make(true);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load categories data.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('components.category.form-modal');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CategoryRequest $categoryRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $categoryRequest)
    {
        try {
            $category = Category::create($categoryRequest->only('name'));

            return Transformer::success('Success to insert category.', new CategoryResource($category), 201);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to insert category.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('components.category.form-modal', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CategoryRequest $categoryRequest
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $categoryRequest, Category $category)
    {
        try {
            $category->update($categoryRequest->only('name'));

            return Transformer::success('Success to update category.', new CategoryResource($category));
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to update category.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return Transformer::success('Success to delete category.');
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to delete category.');
        }
    }

    /**
     * Export categories data.
     *
     * @param   ExportRequest  $exportRequest
     *
     * @return  \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(ExportRequest $exportRequest)
    {
        if ($exportRequest->showView()) {
            return view('components.export-modal', [
                'action' => route('categories.export'),
                'formats' => ExcelHelper::$allowed_export_formats,
            ]);
        }

        $payload = $exportRequest->all();

        return Excel::download(
            new CategoriesExport($payload['take']),
            ExcelHelper::formatExportName('categories', $payload['format'])
        );
    }

    /**
     * Import Categories.
     *
     * @param   ImportRequest  $importRequest
     *
     * @return  mixed
     */
    public function import(ImportRequest $importRequest)
    {
        if ($importRequest->showView()) {
            return view('components.import-modal', [
                'action' => route('categories.import'),
                'datatable' => '#dt-categories',
            ]);
        }

        try {
            Excel::import(new CategoriesImport, $importRequest->file('file'));

            return Transformer::success('Success to import categories.', null, 201);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to import categories.');
        }
    }
}
