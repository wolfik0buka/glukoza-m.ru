<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Image;
use Storage;
use Intervention\Image\Exception\NotReadableException;
use ErrorException;

class Tovar extends Model
{
    protected $table = 'tovar';

    protected $guarded = [
      'id',
    ];

    protected $casts = [
      'podzakaz' => 'Integer',
      'usluga' => 'Integer',
      'price' => 'Double',
      'is_available' => 'Boolean',
    ];

    protected $appends = [
      'is_available',
      'discount',
      'real_price',
      'articul',
      'src',
      'xs_src',
      'md_src',
    ];

    protected $thumbs_sizes = [
      'xs' => ['width' => 100, 'height' => 100],
      'md' => ['width' => 400, 'height' => 400],
    ];

    public static function isServiceProduct($product_id)
    {
        $serviceProductIds = [71, 72, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 383, 384, 385, 386, 387, 388, 389, 390, 391, 392, 393, 394, 395, 396, 397, 398, 399, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 966, 972, 982, 1371, 1372, 1421, 1542, 1707];

        return in_array($product_id, $serviceProductIds);
    }


    public function getSrcAttribute()
    {
        return 'https://cdn.glukoza-med.ru/products/' . $this->attributes['id'] . '/orig.jpg';
    }

    public function getXsSrcAttribute()
    {
        return 'https://cdn.glukoza-med.ru/products/' . $this->attributes['id'] . '/xs.jpg';
    }


    public function getMdSrcAttribute()
    {
        return 'https://cdn.glukoza-med.ru/catalog/' . $this->attributes['id'] . '/md.jpg';
    }


    /**
     * Upload product image in maximum size
     * @param $file
     * @return false|string
     */
    public function upload($file)
    {
        if (is_string($file)) {
            $path = Image::openFromUrl($file)
              ->resize(1200, 1200)
              ->save()
              ->getPath();
        } else {
            try {
                $path = Image::open($file)
                  ->resize(1200, 1200)
                  ->save()
                  ->getPath();
            } catch (NotReadableException $ex) {
                $path = Image::openFromUrl($file)
                  ->resize(1200, 1200)
                  ->save()
                  ->getPath();
            }
        }

        return Storage::disk('local')
          ->put('products/' . $this->attributes['id'] . '/orig.jpg', file_get_contents($path));
    }


    /**
     * Create all sizes of thumbs & upload to selectel
     */
    public function createThumbs()
    {
        try {
            foreach ($this->thumbs_sizes as $name => $sizes) {
                $path = Image::openFromUrl($this->getSrcAttribute())
                  ->resize($sizes['width'], $sizes['height'])
                  ->save()
                  ->getPath();

                $upload = Storage::disk('local')
                  ->put('products/' . $this->attributes['id'] . '/' . $name . '.jpg', file_get_contents($path));

                if ($upload) {
                    $this->update([
                      $name . '_uploaded_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }
            }
        } catch (ErrorException $ex) {
        }
    }


    public function scopeLike($query, $fields, $value)
    {
        $query = $query->where(array_shift($fields), 'LIKE', "%$value%");
        if (!empty($fields)) {
            foreach ($fields as $field) {
                $query = $query->orWhere($field, 'LIKE', "%$value%");
            }
        }
        return $query;
    }

    public function productProperties()
    {
      return $this
        ->belongsToMany('App\Models\PropertyLinkValue', 'property_link_tovar', 'id_tovar', 'id_value');
    }
    public function relatedProducts()
    {

        return $this
          ->belongsToMany('App\Models\Tovar', 'link_product_related', 'product_id', 'related_id');
    }


    public function linksRelatedProducts()
    {
        return $this
          ->hasMany('App\Models\LinkProductRelated', 'product_id', 'id');
    }


    public function linksToCats()
    {
        return $this
          ->hasMany('App\Models\LinkTovar', 'id_tovar', 'id')
          ->where('del', 0);
    }


    public function tovar_1c_att()
    {
        return $this
          ->belongsTo('App\Models\Tovar1c',  'id','id_tovar');
    }


    public function getIsAvailableAttribute()
    {

        if ((int) $this->attributes['del'] === 1) {
            return false;
        }
        if ($this->attributes['podzakaz'] == 1) {
            return true;
        } else {
           //var_export( $this);
            $model_1c = $this->getRelation('tovar_1c_att');
            if ($model_1c) {
                return $model_1c->pres == 1;
            }
        }
    }


    public function getDescFullAttribute()
    {
        $desc = $this->attributes['desc_full'];
        $desc = preg_replace('/ style=("|\')(.*?)("|\')/', '', $desc);
        $desc = str_replace(['<strong>', '</strong>', '<span>', '</span>'], ' ', $desc);
        $desc = preg_replace('/\s+/', ' ', $desc);

        return $desc;
    }


    public function getDiscountAttribute()
    {
        $discount = 0;

        if ($this->attributes['podzakaz'] == 1) {
            $price = $this->attributes['price'];
        } else {
            $model_1c = $this->getRelation('tovar_1c_att');
            if ($model_1c) {
                $price = $model_1c->price;
            }
        }

        if (isset($price)) {
            if (+$this->attributes['price_old'] > 0) {
                return round(100 - (+$price * 100 / +$this->attributes['price_old']));
            }
        }

        return $discount;
    }


    public function getArticulAttribute()
    {
        return str_repeat('0', 5 - strlen($this->attributes['id'])) . $this->attributes['id'];

    }


    public function getRealPriceAttribute()
    {
        $price = 0;
        if ($this->attributes['podzakaz'] == 1) {
            $price = $this->attributes['price'];
        } else {
            $model_1c = $this->getRelation('tovar_1c_att');
            if ($model_1c) {
                $price = $model_1c->price;
            }
        }

        return $price;
    }


}
