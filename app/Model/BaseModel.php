<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function __construct()
    {
    }

    public static function needPage()
    {
        \Illuminate\Pagination\Paginator::currentPathResolver(function(){
            $uri = isset($_GET['_url']) ? $_GET['_url'] : '';
            $uri = ltrim($uri, '/');

            return '/' . $uri;
        });

        \Illuminate\Pagination\Paginator::currentPageResolver(function(){
            $page = isset($_GET['page']) ? $_GET['page'] : 1;

            return $page;
        });

        return get_called_class();
    }

}
