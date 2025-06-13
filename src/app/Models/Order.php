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
            $client = new Client();
            $product = new Product();

            $clientResultFromQuery = $client->getByName($filterByValue);
            $productResultFromQuery = $product->getByName($filterByValue);

            $clientId = !empty($clientResultFromQuery) ? $clientResultFromQuery->id : null;
            $productId = !empty($productResultFromQuery) ? $productResultFromQuery->id : null;

            $collection = $this->where('quantity', '=', $filterByValue);
            if($clientId)
                $collection = $collection->orWhere('client_id', '=', $clientId);
            if($productId)
                $collection = $collection->orWhere('product_id', '=', $productId);

            if(strlen($filterByStatus) > 0)
                $collection = $collection->where('status', $filterByStatus);
            if(strlen($filterByDate) > 0)
                $collection = $collection->where('order_date', '=', $filterByDate);

            $collection = $collection->join('products', 'products.id', '=', 'orders.product_id')->join('clients', 'clients.id', '=', 'orders.client_id')->select('orders.*', 'products.name as product_name', 'clients.name as client_name', 'clients.surname as client_surname');

            if(strtoupper($sortByType) === 'ASC')
                $collection = $collection->orderBy("orders.".$sortByColumn);
            else
                $collection = $collection->orderByDesc("orders.".$sortByColumn);

            $count = $collection->count();
            $data = [
                "total" => $count,
                "items" => $collection
                    ->offset($offset)
                    ->limit($limit)
                    ->get()
            ];

            return $data;
        }else{
            $collection = strtoupper($sortByType) === 'ASC' ? $this->orderBy("orders.".$sortByColumn) : $this->orderByDesc("orders.".$sortByColumn);

            $collection = $collection->join('products', 'products.id', '=', 'orders.product_id')->join('clients', 'clients.id', '=', 'orders.client_id')->select('orders.*', 'products.name as product_name', 'products.price as product_price', 'clients.name as client_name', 'clients.surname as client_surname');

            $count = $collection->count();
            $data = [
                "total" => $count,
                "items" => $collection
                    ->offset($offset)
                    ->limit($limit)
                    ->get()
            ];

            return $data;
        }
    }

    public function removeMany(array $ids)
    {
        if(count($ids) > 0){
            $this->whereIn("id", $ids)->delete();
            return;
        }
        return;
    }
}

