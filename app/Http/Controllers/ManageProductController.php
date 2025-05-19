<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Model\Product as ModelProduct;

class ManageProductController extends Controller
{
    public function index(Request $request)
    {
        $productsByCategory = Product::all()->groupBy('product_category');
        $products = Product::when($request->category, function ($query, $category) {
            return $query->where('product_category', $category);
        })->get();
    
        // Iterate through the products and update the status
        foreach ($products as $product) {
            if ($product->product_quantity == 0) {
                $product->product_status = 'Out of Stock';
            } elseif ($product->product_quantity <= 10) {
                $product->product_status = 'Low Stock';
            } else {
                $product->product_status = 'Available';
            }
    
            // Save the updated product status to the database
            $product->save();
        }
    
        // Pass the products to the view
        return view('ManageProduct.product', compact('products', 'productsByCategory'));
    }

    public function showProductList(Request $request)
    {
        $categories = Product::select('product_category')->distinct()->get();
        $products = Product::when($request->category, function ($query, $category) {
            return $query->where('product_category', $category);
        })->get();
    
        // Iterate through the products and update the status
        foreach ($products as $product) {
            if ($product->product_quantity == 0) {
                $product->product_status = 'Out of Stock';
            } elseif ($product->product_quantity <= 10) {
                $product->product_status = 'Low Stock';
            } else {
                $product->product_status = 'Available';
            }
    
            // Save the updated product status to the database
            $product->save();
        }
    
        // Pass the products to the view
        return view('ManageProduct.product_list', compact('products', 'categories'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function addProduct()
    {
        $productCategory = ['Plate','Cup','Bowl','Bottle','Lunch Box', 'Cultery', 'Others'];
        return view('ManageProduct.add_product_form', compact('productCategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'product_serial_number' => 'required',
            'product_description' => 'required',
            'product_category' => 'required',
            'product_quantity' => 'required',
            'product_purchase_price' => 'required',
            'product_selling_price' => 'required',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('product_image')) {
            $imageName = time() . '.' . $request->product_image->extension();
            $request->product_image->move(public_path('images/products'), $imageName);
        }
    
        $product = Product::create(array_merge($request->all(), ['product_image' => $imageName]));
    
        return redirect()->route('product.list');
    }

    public function viewProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('ManageProduct.view_product_form', compact('product'));
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $productCategory = ['Plate','Cup','Bowl','Bottle','Lunch Box', 'Cultery', 'Others'];

        return view('ManageProduct.edit_product_form', compact('product', 'productCategory'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required',
            'product_serial_number' => 'required',
            'product_description' => 'required',
            'product_category' => 'required',
            'product_quantity' => 'required',
            'product_purchase_price' => 'required',
            'product_selling_price' => 'required',
            'product_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $imageName = $product->product_image;
        // Check if a new image is being uploaded
        if ($request->hasFile('product_image')) {
            // Delete the old image if it exists
            if ($imageName && file_exists(public_path('images/products/' . $imageName))) {
                unlink(public_path('images/products/' . $imageName));
            }

            // Upload the new image
            $imageName = time() . '.' . $request->product_image->extension();
            $request->product_image->move(public_path('images/products'), $imageName);
        }

        $product->update(array_merge($request->except('product_image'), ['product_image' => $imageName]));
    
        return redirect()->route('product.list');
    }

    public function deleteProduct($id) {
        // Find the product by ID
        $product = Product::find($id);
    
        // Check if the product exists and if it has an associated image
        if ($product && $product->product_image) {
            // Define the file path
            $imagePath = public_path('images/products/' . $product->product_image);
            
            // Check if the file exists and delete it
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    
        // Delete the product record from the database
        $product->delete();
    
        // Redirect back to the product list with a success message
        return redirect()->route('product.list')->with('success', 'Product deleted successfully!');
    }    
}
