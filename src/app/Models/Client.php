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
            if(strtoupper($sortByType) === 'ASC')
                return $this->select('*')
                    ->where('name', 'like', "%{$filterByValue}%")
                    ->orWhere('surname', 'like', "%{$filterByValue}%")
                    ->orWhere('email', 'like', "%{$filterByValue}%")
                    ->orWhere('cpf', 'like', "%{$filterByValue}%")
                    ->orderBy($sortByColumn)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            else
                return $this
                    ->where('name', 'like', "%{$filterByValue}%")
                    ->orWhere('surname', 'like', "%{$filterByValue}%")
                    ->orWhere('email', 'like', "%{$filterByValue}%")
                    ->orWhere('cpf', 'like', "%{$filterByValue}%")
                    ->orderByDesc($sortByColumn)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

        }else{
            if(strtoupper($sortByType) === 'ASC')
                return $this->select('*')
                    ->orderBy($sortByColumn)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            else
                return $this
                    ->orderByDesc($sortByColumn)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
        }
    }
}
