<?php 

namespace App\Http\Repositories;
use App\Models\Categories as MainModel;
use App\Helpers\Formatter;

class CategoryRepository
{

    public function list($request)
    {
        $qb = MainModel::query();
       
        $qb = $qb->filter($request)
                ->orderByCus($request)
                ->cursorPaginate(config('params.per_page'));
       
        return $qb;
    }

    public function insert($formFields)
    {
       MainModel::create($formFields);
    }

    public function updateRecord($formFields)
    {
        $record = MainModel::findOrFail($formFields['id']);

        $record->name               = $formFields['name'];
        $record->status             = $formFields['status'];
        $record->ordering           = $formFields['ordering'];
        
        $record->save();

        return $record->id;
    }

    public function updateStatus($formFields)
    {
        $record = MainModel::findOrFail($formFields['id']);
        
        $record->status = Formatter::convertStatus($formFields['status']);
        
        $record->save();
        return $record;
    }

    public function updateOrdering($formFields)
    {
        $record = MainModel::findOrFail($formFields['id']);
        
        $record->ordering = $formFields['ordering'];
        
        $record->save();
        return $record;
    }

    public function deleteData($ids)
    {
        MainModel::whereIn('id', $ids)->delete();
    }

    public function getRecord($id)
    {
        $record = MainModel::findOrFail($id);

        return $record;
    }

    // public function getRecords($ids)
    // {
    //     $records = MainModel::whereIn('id', $ids);

    //     return $records;
    // }
}