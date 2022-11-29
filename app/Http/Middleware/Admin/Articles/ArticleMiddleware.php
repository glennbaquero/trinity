<?php

namespace App\Http\Middleware\Admin\Articles;

use App\Extendables\BaseMiddleware as Middleware;

class ArticleMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.articles.crud'];
    }
}
