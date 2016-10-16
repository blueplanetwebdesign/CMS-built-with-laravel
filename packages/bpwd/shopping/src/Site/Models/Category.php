<?php

namespace Bpwd\Shopping\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'shopping_categories';

    protected $fillable = ['description', 'name'];

}