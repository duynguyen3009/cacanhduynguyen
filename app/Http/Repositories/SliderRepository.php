<?php 

namespace App\Http\Repositories;
use App\Models\Sliders as MainModel;

class SliderRepository
{

    public function list($request)
    {
        $defaultOrderBy = $request['defaultOrderBy'] ?? [];
        $qb             = MainModel::query();
       
        if (!empty($defaultOrderBy)) {
            foreach ($defaultOrderBy as $column => $v) {
                $qb = $qb->orderBy($column, $v);
            }
        }

        $qb = $qb->filter($request)->cursorPaginate(config('params.per_page'));
       
        return $qb;
    }

    public function insert($formFields)
    {
        $id = MainModel::create($formFields)->id;

        return $id;
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
        $record = MainModel::findOrFail($formFields['id']);
        
        $status = ($formFields['status'] == 'active') ? 'inactive' : 'active';
        $record->status = $status;
        
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

    public function getRecords($ids)
    {
        $records = MainModel::whereIn('id', $ids);

        return $records;
    }
}