<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Product;

class Order extends Model
{
    public function create(array $data)
    {
        $this->fill($data)->save();
        return;
    }

    public function updateData(int $id, array $data)
    {
        $this->where('id', $id)->update($data);
        return;
    }

    public function remove(int $id)
    {
        $this->where("id", $id)->delete();
        return;
    }

    public function getPaginatedSortedAndFiltered(int $offset=0, int $limit=20, string $sortByColumn='id', string $sortByType='ASC', string $filterByValue='', string $filterByDate='', string $filterByStatus='')
    {
        if(strlen($filterByValue) > 0 || strlen($filterByDate) > 0 || strlen($filterByStatus) > 0){
            $client_id = Client::where('name', 'like', "%{$filterByValue}%")->first()?->id ?? Client::Where('surname', 'like', "%{$filterByValue}%")?->id;
            $product_id = Product::where('name', 'like', "%{$filterByValue}%")
            ->first()?->id;

            $collection = $this->where('quantity', '=', $filterByValue);
            if($client_id)
                $collection = $collection->orWhere('client_id', '=', $client_id);
            if($product_id)
                $collection = $collection->orWhere('product_id', '=', $product_id);

            if(strlen($filterByStatus) > 0)
                $collection = $collection->where('status', $filterByStatus);
            if(strlen($filterByDate) > 0)
                $collection = $collection->where('order_date', '=', $filterByDate);


            if(strtoupper($sortByType) === 'ASC')
                $collection = $collection->orderBy($sortByColumn);
            else
                $collection = $collection->orderByDesc($sortByColumn);

            return $collection
                ->offset($offset)
                ->limit($limit)
                ->get();
        }else{
            $collection = strtoupper($sortByType) === 'ASC' ? $this->orderBy($sortByColumn) : $this->orderByDesc($sortByColumn);

            return $collection
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
        }
    }
}
