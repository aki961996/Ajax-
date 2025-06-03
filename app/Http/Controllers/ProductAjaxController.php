<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class ProductAjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $product = Product::latest()->get(); // Now always defined
 

    if ($request->ajax()) {
        return Datatables::of($product)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn  = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
                $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('productAjax', compact('product'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request )
    {
     
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
   
    // ✅ Step 1: Validate request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // ✅ Step 2: Create or update product
    $product = Product::updateOrCreate(
        ['id' => $request->id], // Match by ID (null for create, id for update)
        [
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]
    );

    // ✅ Step 3: Return response
    return response()->json([
        'success' => 'Product saved successfully.',
        'data' => $product
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit($id)
    {

        $product = Product::find($id);
        return response()->json($product);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
        
        Product::find($id)->delete();
     
        return response()->json(['success'=>'Product deleted successfully.']);
    }
}
