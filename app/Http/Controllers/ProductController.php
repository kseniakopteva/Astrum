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

        $u = auth()->user();
        $price = 10;
        if ($u->stars < $price) {
            return back()
                ->with('error', 'You don\'t have enough money!');
        }

        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'type' => 'required',
            'max_slots' => 'numeric|nullable',
            'description' => 'required|max:2000',
            'price' => 'required|numeric',
            'currency' => 'required',
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        $path = public_path('images\\products');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        $image = Image::make($path . '/' . $imageName)->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->save($path . '/' . $imageName);

        $new_product = Product::create($attributes);
        $u->stars -= $price;
        $u->save();

        $tags = array_filter(array_map('trim', explode(',', $request['tags'])));
        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag], ['slug' => str_replace(' ', '_', $tag)])->save();
        }
        $tags = Tag::whereIn('name', $tags)->get()->pluck('id');
        $new_product->tags()->sync($tags);

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

        return redirect()->back()->with('success', 'Order submitted!');
    }

    public function destroy(Request $request)
    {
        $product = Product::find($request->product_id);
        if ($product->author->id !== auth()->user()->id) {
            return back()->with('error', 'You can\'t do that.');
        }

        unlink(public_path('images/products/' . $product->image));
        $product->delete();
        return back()->with('success', 'Product deleted!');
    }
}
