<?php

namespace App\Models\Products;

use App\Extendables\BaseModel as Model;

use App\Helpers\StringHelpers;
use App\Traits\FileTrait;
use Laravel\Scout\Searchable;

use App\Models\Carts\Cart;
use App\Models\Carts\CartItem;
use App\Models\Invoices\Invoice;
use App\Models\Inventories\Inventory;
use App\Models\Specializations\Specialization;
use App\Models\Users\User;
use App\Models\Prescriptions\Prescription;
use App\Models\Products\ProductParent;

class Product extends Model
{

    use FileTrait;
    use Searchable;


	protected $guarded = [];

    protected $appends = ['full_image'];

    /*
    |--------------------------------------------------------------------------
    | @Const
    |--------------------------------------------------------------------------
    */
        
    const NON_PHARMACEUTICAL = 1;
    const PHARMACEUTICAL = 2;


    /*
    |--------------------------------------------------------------------------
    | @Relationships
    |--------------------------------------------------------------------------
    */

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function specializations()
    {
         return $this->belongsToMany(Specialization::class, 'product_specializations', 'specialization_id', 'product_id')->withTrashed();
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'product_user', 'user_id', 'product_id');
    }

    public function parent()
    {
        return $this->belongsTo(ProductParent::class, 'parent_id')->withTrashed();
    }


    /*
    |--------------------------------------------------------------------------
    | @Methods
    |--------------------------------------------------------------------------
    */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'brand_name' => $this->brand_name,
            'name' => $this->name,
            'generic_name' => $this->generic_name,
            'sku' => $this->sku,
        ];
    }
   

    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {
        $admin = \Auth::guard('admin')->user();

        $vars = $request->except(['prescriptionable', 'is_other_brand', 'images', 'initial_stocks','specialization_ids']);
    
        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'products');
        $vars['prescriptionable'] = $request->prescriptionable ? 1: 0;
        $vars['is_other_brand'] = $request->is_other_brand ? 1: 0;
        $vars['is_free_product'] = $request->is_free_product ? 1: 0;
        $vars['new_arrival'] = $request->new_arrival ? 1: 0;
        $vars['best_seller'] = $request->best_seller ? 1: 0;
        $vars['promo'] = $request->promo ? 1: 0;

        if(!$item) {
            $item = static::create($vars);

            /** Create initial stocks */
            $item->inventory()->create(['stocks' => $request->initial_stocks]);
        } else {
            $item->update($vars);
        }

        $item->specializations()->sync($request->specialization_ids);

        return $item;
    }

    /**
     * Append image full path
     * 
     * @return string $image
     */
    public function getFullImageAttribute()
    {
        return url($this->renderImagePath());
    }

    /**
     * Get random products
     * 
     */
    public static function getRandom()
    {
        $products = self::inRandomOrder()->take(6)->get();

        $products = collect($products)->map(function($product) {
            $product->stocks = $product->inventory ? $product->inventory->stocks : 0;
            return $product;
        });

        return $products;
    }


    /*
    |--------------------------------------------------------------------------
    | @Renders
    |--------------------------------------------------------------------------
    */

    public function renderStocks() 
    {
        if($this->inventory) {
            return $this->inventory->stocks;
        }
        return 0;
    }

    public function renderFilteredProductName()
    {
        $name = str_replace(' ', '-', strtolower($this->name));
        return preg_replace('/[^A-Za-z0-9\-]/', '-', $name);
    }

    /**
     * Render if product has prescription
     * 
     * @return String
     */
    public function renderPrescriptionable()
    {
        if($this->prescriptionable) {
            return "Yes";
        }
        return "No";
    }

    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl($prefix = 'admin')
    {
        return route(StringHelpers::addRoutePrefix($prefix) . 'products.show', $this->id);
    }

    public function renderCreateVariantUrl()
    {
        return route('admin.products.create.variant', [$this->id, $this->renderFilteredProductName()]);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.products.archive', $this->id);
    }


    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.products.restore', $this->id);
    }

    /**
     * Render points
     * 
     * @return string
     */
    public function renderClientPoints()
    {
        if(fmod($this->client_points, round($this->client_points)) === 0.00) {
            $client_points = round($this->client_points);
        } else {
            $client_points = $this->client_points;
        }

        return $client_points;
    }

    public function renderParentName()
    {
        if($this->parent) {
            return $this->parent->name;
        }
        return 'N/A';
    }

    public function renderBoughtStatus($user) 
    {
        $relatedProducts = $user->products;

        foreach ($relatedProducts as $key => $parent) {
            $exists = $parent->products->where('id', $this->id)->count();

            if($exists) {
                return true;
            }
        }
        return false;
    }

    /**
     * Render product type
     * 
     * @return String
     */
    public function renderType() 
    {
        switch ($this->type) {
            case static::NON_PHARMACEUTICAL:
                return 'Non-pharmaceutical product';
                break;
            
            case static::PHARMACEUTICAL:
                return 'Pharmaceutical product'; 
                break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | @Checkers
    |--------------------------------------------------------------------------
    */

}
