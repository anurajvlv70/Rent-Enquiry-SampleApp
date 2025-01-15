<?php

namespace App\Http\Controllers;

use App\Models\enquiry;
use App\Models\Product;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Display records based on type (inquiries or products).
     */
    public function index($type)
    {
        // dd($type);
        // dd(vars: $type->all());
        $records = Product::all();
        if ($type === 'enquiry') {
            $enquiries = enquiry::all();
            return view('enquiry', ['enquiries' => $enquiries,'products' => $records]);
        } elseif ($type === 'rental') {
            $records = Product::all();
            //dd($records);
            return view('rental', ['products' => $records]);
        }
        abort(404, 'Page not found');
    }

    /**
     * Store or update records.
     */
    public function store(Request $request)
    {
        $mode = $request->input('mode'); // Either 'inquiry' or 'product'
        $operation = $request->input('operation'); // Either 'create' or 'edit'
//dd(vars: $request->all());
        if ($mode === 'enquiry') {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            if ($operation === 'create') {
                enquiry::create($validated);
                return redirect()->back()->with('success', 'Inquiry added successfully!');
            } elseif ($operation === 'edit') {
                $inquiryId = $request->input('inquiryId');
                $inquiry = enquiry::findOrFail($inquiryId);
                $inquiry->update($validated);
                return redirect()->back()->with('success', 'Inquiry updated successfully!');
            }
        } elseif ($mode === 'rental') {
         //dd($mode);
            if($request->input('operation')!=='delete')
            {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100',
                'available_stock' => 'required|integer|min:0',
                'image' => 'nullable|image|max:2048',
            ]);
        }
        else {
            $validated = $request->validate([
                'productId' => 'required',
              
            ]);
        }

            if ($operation === 'create') {
                // dd('checking');
                $data = $validated;
                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('products', 'public');
                }
                Product::create($data);
                return redirect()->back()->with('success', 'Product added successfully!');
            } elseif ($operation === 'edit') {
                $productId = $request->input('productId');
                $product = Product::findOrFail($productId);
                $data = $validated;
                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('products', 'public');
                }
                $product->update($data);
                return redirect()->back()->with('success', 'Product updated successfully!');
            }
            elseif ($operation === 'delete') {

               
                $productId = $request->input('productId');
                // dd($productId);
                $product = Product::findOrFail($productId);
            
                // Optionally, delete the image from storage if it exists
                if ($product->image && \Storage::exists('public/' . $product->image)) {
                    \Storage::delete('public/' . $product->image);
                }
            
                $product->delete();
                return redirect()->back()->with('success', 'Product deleted successfully!');
            
        }

        abort(400, 'Invalid operation');
    }
}
}