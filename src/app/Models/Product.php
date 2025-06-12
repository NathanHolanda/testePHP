<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'barcode',
    ];

    public function getById(int $id)
    {
        return $this->find($id);
    }

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

    public function deleteData(int $id)
    {
        $this->where("id", $id)->delete();
        return;
    }

    public function getPaginatedSortedAndFiltered(int $offset=0, int $limit=20, string $sortByColumn='id', string $sortByType='ASC', string $filterByValue='')
    {
        if(strlen($filterByValue) > 0){
            $collection = $this
                    ->where('name', 'like', "%{$filterByValue}%")
                    ->orWhere('barcode', 'like', "%{$filterByValue}%")
                    ->orWhere('price', '=', str_replace(',', '.', $filterByValue));

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
