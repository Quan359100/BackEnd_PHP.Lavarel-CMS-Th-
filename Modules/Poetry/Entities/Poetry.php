<?php

namespace Modules\Poetry\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Poetry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'poems'; 
    protected $fillable = [
        'title', 'slug', 'description', 'deleted_at', 'created_by', 'updated_by', 'deleted_by','status'
    ];

    /**
     * category
     *
     * @return object returns the category model as obejct
     */
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    // public static function serviceByCategory($category_id)
    // {
    //     return Service::where('category_id', $category_id)->select('id', 'category_id', 'slug', 'title')->orderBy('id', 'desc')->get();
    // }

    public static function getPageData($args = [])
    {
        $data = [
            'pages' => []
        ];

        $defaults = [
            'lang'            => 'en',
            'orderBy'         => 'desc',
            'orderByCol'      => 'id',
            'limit'           => 5,
            'paginate'        => false,
            'paginateNo'      => 20,
            'slug'            => null,
            'single'          => false,
            'single_column'   => 'pd'
        ];

        $args = array_merge($defaults, $args);

        $query = DB::table('poems')->where('deleted_at', null);

        if (!empty($args['limit'])) {
            $query->limit($args['limit']);
        }

        if (!empty($args['orderByCol'])) {
            $query->orderBy($args['orderByCol'], $args['orderBy']);
        }

        if ($args['paginate']) {
            $query->paginate($args['paginateNo']);
        }

        if (!empty($args['slug'])) {
            $query->where('slug', $args['slug']);
        }

        if ($args['single']) {
            $page = count($query->get()) > 0 ? $query->get()[0] : null;
            $data['pages'] = $page;
        } else {
            $data['pages'] = $query->get();
        }

        return $data;
    }
}