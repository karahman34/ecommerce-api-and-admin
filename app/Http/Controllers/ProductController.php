<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Helpers\ExcelHelper;
use App\Http\Requests\ExportRequest;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductImageResource;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\ProductImage;
use App\Utils\Transformer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->wantsJson()) {
            return view('products', ['title' => 'Products']);
        }

        $products = Product::with('thumbnail:id,product_id,path');

        return DataTables::of($products)
                            ->addColumn('thumbnail', function (Product $product) {
                                return $product->thumbnail->fullPathUrl();
                            })
                            ->addColumn('actions', function (Product $product) {
                                return view('components.datatables.actions-button', [
                                    'item' => $product,
                                    'item_title' => $product->name,
                                    'edit_url' => route('products.edit', ['product' => $product->id]),
                                    'delete_url' => route('products.destroy', ['product' => $product->id]),
                                    'modal' => '#product-form-modal',
                                    'datatable' => '#dt-products',
                                ]);
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('components.product.form-modal');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ProductRequest  $productRequest
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $productRequest)
    {
        try {
            $payload = $productRequest->only([
                'category_id',
                'name',
                'stock',
                'price',
                'description',
            ]);

            $product = Product::create($payload);

            $this->insertProductImages($productRequest, $product);

            return Transformer::success('Success to insert new product.', new ProductResource($product), 201);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to insert product data.');
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('components.product.form-modal', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function editProductImage(Product $product, ProductImage $productImage)
    {
        return view('components.product.edit-image-modal', [
            'product' => $product,
            'productImage' => $productImage
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\ProductRequest  $productRequest
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $productRequest, Product $product)
    {
        try {
            $payload = $productRequest->only([
                'category_id',
                'name',
                'stock',
                'price',
                'description',
            ]);

            $this->insertProductImages($productRequest, $product);

            $product->update($payload);

            return Transformer::success('Success to update product data.', new ProductResource($product));
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to update product data.');
        }
    }

    /**
     * Update product image.
     *
     * @param   Request       $request
     * @param   Product       $product
     * @param   ProductImage  $productImage
     *
     * @return  \Illuminate\Http\Response
     */
    public function updateProductImage(Request $request, Product $product, ProductImage $productImage)
    {
        $request->validate([
            'image' => 'required|file|mimes:png,jpg,jpeg,bmp|max:4096',
        ]);

        try {
            if (!$product->images()->where('path', $productImage->path)->exists()) {
                return Transformer::failed('Product image not found.', null, 404);
            }

            if ($request->hasFile('image')) {
                $this->deleteProductImageFromStorage($productImage);

                $productImage->update([
                    'path' => $this->storeProductImage($request->file('image')),
                ]);
            }

            return Transformer::success('Success to update product image.', new ProductImageResource($productImage));
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to update product image.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $this->deleteProductImages($product);

            $product->delete();

            return Transformer::success('Success to delete product item.');
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to delete product item.');
        }
    }

    /**
     * Delete product image.
     *
     * @param   Product       $product
     * @param   ProductImage  $productImage
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyProductImage(Product $product, ProductImage $productImage)
    {
        try {
            if (!$product->images()->where('path', $productImage->path)->exists()) {
                return Transformer::failed('Product image not found.', null, 404);
            }

            if ($product->images()->count() === 1) {
                return Transformer::failed('You can delete the image only when the images is more than 1.', null, 400);
            }

            $this->deleteProductImageFromStorage($productImage);

            $productImage->delete();

            return Transformer::success('Success to delete product image.');
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to delete product image.');
        }
    }

    /**
     * Store the uploaded product images.
     *
     * @param   Request  $request
     * @param   Product  $product
     *
     * @return  void
     */
    private function insertProductImages(Request $request, Product $product)
    {
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $file) {
                $product->images()->create([
                    'path' => $this->storeProductImage($file),
                ]);
            }
        }
    }

    /**
     * Store product image into storage.
     *
     * @param   UploadedFile    $uploadedFile
     *
     * @return  string
     */
    private function storeProductImage(UploadedFile $uploadedFile)
    {
        return $uploadedFile->store(Product::$uploadFolder);
    }

    /**
     * Delete all product images.
     *
     * @param   Product  $product
     *
     * @return  void
     */
    private function deleteProductImages(Product $product)
    {
        if ($product->images) {
            $product->images->map(function (ProductImage $productImage) {
                $this->deleteProductImageFromStorage($productImage);
            });

            $product->images()->delete();
        }
    }

    /**
     * Delete product image from storage.
     *
     * @param   ProductImage  $productImage
     *
     * @return  bool
     */
    public function deleteProductImageFromStorage(ProductImage $productImage)
    {
        return Storage::delete($productImage->path);
    }

    /**
     * Export products data.
     *
     * @param   ExportRequest  $exportRequest
     *
     * @return  \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(ExportRequest $exportRequest)
    {
        if ($exportRequest->showView()) {
            return view('components.export-modal', [
                'action' => route('products.export'),
                'formats' => ExcelHelper::$allowed_export_formats,
            ]);
        }

        $payload = $exportRequest->all();

        return Excel::download(new ProductsExport($payload['take']), ExcelHelper::formatExportName('products', $payload['format']));
    }

    /**
     * Import Products.
     *
     * @param   ImportRequest  $importRequest
     *
     * @return  mixed
     */
    public function import(ImportRequest $importRequest)
    {
        if ($importRequest->showView()) {
            return view('components.import-modal', [
                'action' => route('products.import'),
                'datatable' => '#dt-products',
            ]);
        }

        try {
            Excel::import(new ProductsImport, $importRequest->file('file'));

            return Transformer::success('Success to import products.', null, 201);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to import products.');
        }
    }
}
