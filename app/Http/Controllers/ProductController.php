<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        if (auth()->user()->isBanned())
            return back()->with('success', 'You can\'t add a product because you are banned.');

        $u = auth()->user();
        $price = 10;
        if ($u->stars < $price) {
            return back()
                ->with('success', 'You don\'t have enough money!');
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

        $attributes['available_slots'] = $attributes['max_slots'];
        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        $path = storage_path('app\public\images\\products');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        $image = Image::make($path . '\\' . $imageName)->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->save($path . '\\' . $imageName);

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
}
