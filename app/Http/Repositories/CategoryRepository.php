<?php 

namespace App\Http\Repositories;
use App\Models\Categories as MainModel;
use App\Helpers\AdminProcess;

class CategoryRepository
{
    protected $table = 'categories';
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
        AdminProcess::updateStatus($this->table, $formFields);
    }

    public function updateOrdering($formFields)
    {
        AdminProcess::updateOrdering($this->table, $formFields);
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

}