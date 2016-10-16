<?php

namespace Bpwd\Shopping\Site\Controllers;

use App\Http\Controllers\Controller;

use Bpwd\Shopping\Site\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function All()
    {
        $categories = Category::all();

        return view('shopping-site::Categories', ['categories' => $categories]);
    }

}
