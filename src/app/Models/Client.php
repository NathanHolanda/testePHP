<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'email',
        'cpf',
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
            $cpfFilter = preg_replace('/\D/', '', $filterByValue);

            $collection = $this
                    ->where('name', 'like', "%{$filterByValue}%")
                    ->orWhere('surname', 'like', "%{$filterByValue}%")
                    ->orWhere('email', 'like', "%{$filterByValue}%");

            if(strlen($cpfFilter) > 0)
                $collection = $collection
                    ->orWhere('cpf', 'like', "%".$cpfFilter."%");

            if(strtoupper($sortByType) === 'ASC')
                $collection = $collection->orderBy($sortByColumn);
            else
                $collection = $collection->orderByDesc($sortByColumn);

            return $collection->offset($offset)
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
