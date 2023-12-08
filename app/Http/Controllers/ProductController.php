<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t add a product because you are banned.');

        // price to make a product is 10 stars
        $price = 10;
        $u = auth()->user();
        if ($u->stars < $price) {
            return back()
                ->with('error', 'You don\'t have enough money!');
        }

        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'type' => 'required',
            'max_slots' => 'numeric|nullable',
            'description' => 'required|max:2000',
            'price' => 'required|numeric',
            'currency' => 'required',
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        // saving image, resizing it without making it larger than it is, saving the aspect ratio
        $path = public_path('images\\products');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);
        Image::make($path . '/' . $imageName)->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        Product::create($attributes);
        $u->stars -= $price;
        $u->save();

        return redirect()->route('profile.shop', ['author' => auth()->user()->username])
            ->with('success', 'You have successfully added a product!');
    }

    public function buy(Request $request)
    {
        if (!auth()->check())
            return redirect()->route('login');

        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t buy anything because you are banned.');

        $attributes = $request->validate([
            'email' => 'required|email|max:100',
            'details' => 'required|max:2000',
        ]);

        // if bought product's currency is stars, remove the price amount from buyer's stars
        $bought_product = Product::find($request->product_id);
        if ($bought_product->currency == 'stars') {
            $u = User::find($request->buyer_id);
            $u->stars -= $bought_product->price;
            $u->save();
        }

        $attributes['product_id'] = $request->product_id;

        $attributes['buyer_id'] = $request->buyer_id;
        $attributes['seller_id'] = $request->seller_id;

        Order::create($attributes);

        return redirect()->back()->with('success', 'You have successfully submitted the order!');
    }

    public function destroy(Request $request)
    {
        $product = Product::find($request->product_id);

        // if trying to delete someone else's product
        if ($product->author->id !== auth()->user()->id) {
            return back()->with('error', 'You can\'t do that.');
        }

        // before deleting the product, remove the image from the storage
        unlink(public_path('images/products/' . $product->image));

        $product->delete();
        return back()->with('success', 'You have deleted the product!');
    }
}
