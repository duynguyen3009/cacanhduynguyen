<?php 

namespace App\Http\Repositories;
use App\Models\Sliders as MainModel;
use App\Helpers\AdminProcess;

class SliderRepository
{
    protected $table    = 'sliders';
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

        $record->image              = $formFields['image'];
        $record->name               = $formFields['name'];
        $record->href               = $formFields['href'];
        $record->description        = $formFields['description'];
        $record->status             = $formFields['status'];
        $record->ordering           = $formFields['ordering'];
        $record->date_show_start    = $formFields['date_show_start'];
        $record->date_show_end      = $formFields['date_show_end'];
        
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

    public function getRecords($ids)
    {
        $records = MainModel::whereIn('id', $ids);

        return $records;
    }
}