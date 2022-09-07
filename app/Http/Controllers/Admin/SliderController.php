<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\SliderRepository;
use App\Http\Requests\Admin\SliderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use DB;

class SliderController extends AdminController
{
    protected $sliderRepository;
    protected $fieldsAcceptSearch   = ['id', 'name', 'href', 'description'];
    protected $fieldsAcceptOrdering = [ // show select để orderBy
        ''              => 'Sắp xếp',
        'id'            => 'Id', 
        'name'          => 'Tên', 
        'href'          => 'Href', 
        'description'   => 'Miêu tả',
        'ordering'      => 'Vị trí',
    ];
    protected $defaultOrderBy = [
        'ordering'  => 'DESC', 
        'id'        => 'DESC'
    ];
    protected $storagePath = 'public'. DIRECTORY_SEPARATOR. 'sliders' . DIRECTORY_SEPARATOR ;
    protected $defaultImg  = 'default.jpg';

    public function __construct(
        SliderRepository $sliderRepository
    ) {
        $this->sliderRepository = $sliderRepository;
    }

    public function index()
    {
        $request                            = request()->all();
        $request['fieldsAcceptSearch']      = $this->fieldsAcceptSearch;
        $request['fieldsAcceptOrdering']    = $this->fieldsAcceptOrdering;
        $request['defaultOrderBy']          = $this->defaultOrderBy;

        $records = $this->sliderRepository->list($request);

        return view('admin.slider.index', compact('records', 'request'));
    }

    public function form()
    {
        $formFields = request()->all();
        $record = null;

        //case edit
        if (isset($formFields['id'])) {
            $record = $this->sliderRepository->getRecord($formFields['id']);
        }

        $request = Arr::only($formFields, ['transform_search']);

        return view('admin.slider.form', compact('request', 'record'));
    }

    public function store(SliderRequest $request)
    {
        $formFields     = $request->all();
        $file           = $request->file('image');
        $fileName       = null;
        if (!empty($file)) {
            $fileName               = date("YmdHms") . '.' .$file->extension();
            $formFields['image']    = $fileName;
        } 
        
        $formFieldsAccept       = Arr::only($formFields, ['id', 'image', 'name', 'href', 'description' ,'status', 'ordering', 'date_show_start', 'date_show_end']);

        //case edit
        if (isset($formFields['id'])) {
            if (isset($formFieldsAccept['image'])) { // chọn ảnh mới
                $imageName      = $formFields['old_image'];
                $pathImageName  = storage_path('app' . DIRECTORY_SEPARATOR . $this->storagePath . $imageName);
                if (!empty($imageName)) {
                    if (file_exists($pathImageName)) {
                        unlink($pathImageName);
                    } 
                } 
                $file->storeAs($this->storagePath, $fileName); 

            } else { // không chọn ảnh mới
                $formFieldsAccept['image'] = $formFields['old_image'];
            }
            $id = $this->sliderRepository->updateRecord($formFieldsAccept);
            session()->flash('action_success', __('messages.update_success', ['attribute' => $id]));
            
        } else {
            $file->storeAs($this->storagePath, $fileName); 
            $this->sliderRepository->insert($formFieldsAccept);
            session()->flash('action_success', __('messages.insert_success', ['attribute' => $formFieldsAccept['name']]));
        }
        return response()->json([
            'success'   => true,
            'url'       => route('admin.slider.index'),
        ]);

    }

    public function deleteData(Request $request)
    {
        $request    = $request->all();
        $ids        = array_keys($request);

        try {
            DB::beginTransaction();

            // delete image
            $records = $this->sliderRepository->getRecords($ids)->get(); // lấy nhiều dòng, dựa vào ids
            foreach ($records as $record) {
                $imageName      = $record->image;
                $pathImageName  = storage_path('app' . DIRECTORY_SEPARATOR .$this->storagePath . $imageName);
                if (!empty($imageName)) {
                    if (file_exists($pathImageName)) {
                        unlink($pathImageName);
                    } 
                } else {
                    continue;
                }
            }
            // delete database
            $record     = $this->sliderRepository->deleteData($ids);

            DB::commit();
            return response()->json([
                'success' => true,
                'msg' => __('messages.delete_recored_success', ['ids' => implode(', ', $ids)]),
            ]);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            DB::rollBack();
        }
        
        
    }
}
