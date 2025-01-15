<?php

namespace App\Http\Controllers;

use App\Models\enquiry;
use App\Models\Product;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = enquiry::with('products')->get();
        return view('enquiries.index', compact('enquiries'));
    }

    public function create()
    {
        $products = Product::all();
        return view('enquiries.create', compact('products'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'rental_start_date' => 'required|date',
        'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
        'products' => 'required|array',
        'quantities' => 'required|array',
    ]);

    foreach ($validated['products'] as $productId) {
        $quantity = $validated['quantities'][$productId];

        $overlap = \DB::table('enquiry_products')
            ->join('enquiries', 'enquiry_products.enquiry_id', '=', 'enquiries.id')
            ->where('enquiry_products.product_id', $productId)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('enquiries.rental_start_date', [$validated['rental_start_date'], $validated['rental_end_date']])
                      ->orWhereBetween('enquiries.rental_end_date', [$validated['rental_start_date'], $validated['rental_end_date']])
                      ->orWhereRaw('? BETWEEN enquiries.rental_start_date AND enquiries.rental_end_date', [$validated['rental_start_date']])
                      ->orWhereRaw('? BETWEEN enquiries.rental_start_date AND enquiries.rental_end_date', [$validated['rental_end_date']]);
            })
            ->sum('enquiry_products.quantity');

        $product = Product::find($productId);

        if ($quantity + $overlap > $product->available_stock) {
            return back()->withErrors(['products' => "Product ID {$productId} does not have enough stock."]);
        }
    }

    $enquiry = Enquiry::create([
        'title' => $validated['title'],
        'rental_start_date' => $validated['rental_start_date'],
        'rental_end_date' => $validated['rental_end_date'],
    ]);

    foreach ($validated['products'] as $productId) {
        $quantity = $validated['quantities'][$productId];
        $enquiry->products()->attach($productId, ['quantity' => $quantity]);

        // Decrement the stock
        $product = Product::find($productId);
        $product->decrement('available_stock', $quantity);
    }

    return redirect()->route('rental', parameters: 'enquiry')->with('success', 'Enquiry created successfully.');
}

    public function edit($id)
    {
        $enquiry = enquiry::with('products')->findOrFail($id);
        $products = Product::all();

        return view('enquiries.edit', compact('enquiry', 'products'));
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'rental_start_date' => 'required|date',
        'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
        'products' => 'required|array',
        'quantities' => 'required|array',
    ]);

    $enquiry = Enquiry::findOrFail($id);

    // Restore stock for the currently associated products
    foreach ($enquiry->products as $product) {
        $product->increment('available_stock', $product->pivot->quantity);
    }

    // Detach all products
    $enquiry->products()->detach();

    foreach ($validated['products'] as $productId) {
        $quantity = $validated['quantities'][$productId];

        $overlap = \DB::table('enquiry_products')
            ->join('enquiries', 'enquiry_products.enquiry_id', '=', 'enquiries.id')
            ->where('enquiry_products.product_id', $productId)
            ->where(function ($query) use ($validated, $id) {
                $query->whereBetween('enquiries.rental_start_date', [$validated['rental_start_date'], $validated['rental_end_date']])
                      ->orWhereBetween('enquiries.rental_end_date', [$validated['rental_start_date'], $validated['rental_end_date']])
                      ->orWhereRaw('? BETWEEN enquiries.rental_start_date AND enquiries.rental_end_date', [$validated['rental_start_date']])
                      ->orWhereRaw('? BETWEEN enquiries.rental_start_date AND enquiries.rental_end_date', [$validated['rental_end_date']])
                      ->where('enquiries.id', '!=', $id);
            })
            ->sum('enquiry_products.quantity');

        $product = Product::find($productId);

        if ($quantity + $overlap > $product->available_stock) {
            return back()->withErrors(['products' => "Product ID {$productId} does not have enough stock."]);
        }

        $enquiry->products()->attach($productId, ['quantity' => $quantity]);

        // Decrement the stock
        $product->decrement('available_stock', $quantity);
    }

    $enquiry->update([
        'title' => $validated['title'],
        'rental_start_date' => $validated['rental_start_date'],
        'rental_end_date' => $validated['rental_end_date'],
    ]);

    return redirect()->route('rental', parameters: 'enquiry')->with('success', 'Enquiry created successfully.');
}

public function destroy($id)
{
    $enquiry = Enquiry::findOrFail($id);

    foreach ($enquiry->products as $product) {
        $product->increment('available_stock', $product->pivot->quantity);
    }

    $enquiry->products()->detach();
    $enquiry->delete();

    return redirect()->route('rental', parameters: 'enquiry')->with('success', 'Enquiry created successfully.');
}

}
