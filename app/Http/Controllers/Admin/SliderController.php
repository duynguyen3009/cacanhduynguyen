<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SliderRepository;
use App\Http\Requests\Admin\SliderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use DB;

class SliderController extends Controller
{
    protected $sliderRepository;
    protected $fieldsAcceptSearch   = ['id', 'name', 'href', 'description'];
    protected $fieldsAcceptOrdering = [ // show select để orderBy
        ''              => 'Sắp xếp',
        'id'            => 'Id', 
        'name'          => 'Tên', 
        'href'          => 'Href', 
        'description'   => 'Miêu tả'
    ];
    protected $defaultOrderBy = [
        'ordering'  => 'DESC', 
        'id'        => 'DESC'
    ];
    protected $storagePath = 'public/sliders' . DIRECTORY_SEPARATOR ;
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

        // chỗ này sẽ dùng lại nhiều lần
        if (isset($request['o']) && $request['o'] != null) {
            $collectDefaultOrderBy = collect($this->defaultOrderBy);
            $collectDefaultOrderBy->prepend('DESC', $request['o']);
            $request['defaultOrderBy'] = $collectDefaultOrderBy->all();
        } 

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
        $formFields             = $request->all();
        $formFields['image']    = $formFields['image'] == null ? $this->defaultImg : $formFields['image'];
        $transformSearch        = Arr::only(request()->all(), ['transform_search']);
        $formFieldsAccept       = Arr::only($formFields, ['id', 'image', 'name', 'href', 'description' ,'status', 'ordering', 'date_show_start', 'date_show_end']);
        //case edit
        if (isset($formFields['id'])) {
            $id = $this->sliderRepository->updateRecord($formFieldsAccept);
            return redirect()
                    ->route('slider', @$transformSearch['transform_search'])
                    ->with('action_success', 'Bạn đã sửa thành công slider có id: ' . $id);
        } else {
            $id = $this->sliderRepository->insert($formFieldsAccept);
            return redirect()
                    ->route('slider', @$transformSearch['transform_search'])
                    ->with('action_success', 'Bạn đã thêm thành công slider có id: ' . $id);
        }

    }

    #php artisan storage:link
    public function uploadImage(Request $request)
    {
        $file       = $request->file('file_image');
        $extsAccept = ['jpg', 'png'];
        $ext        = $file->extension();
        if (!in_array($ext, $extsAccept)) {
            return response()->json([
                'error' => true,
                'msg'   => __('messages.incorrect_extension'),
            ]);
        }
        $fileName   = date("YmdHms") . $file->getClientOriginalName();
        $file->storeAs($this->storagePath, $fileName); 

        return response()->json([
            'file_name'      => $fileName,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request = $request->all();

        $classAfterUpdateStatus = ($request['current_class'] == 'success')  ? 'secondary' : 'success';
        $textAfterUpdateStatus  = ($request['current_class'] == 'success')  ? 'Chưa kích hoạt' : 'Kích hoạt';
        $valueAfterUpdateStatus = ($request['current_class'] == 'success')  ? 'inactive' : 'active';

        $record      = $this->sliderRepository
                     ->updateStatus(Arr::only($request, ['id', 'status']));

        return response()->json([
            'success'               => true,
            'id'                    => $record->id,
            'class_after_update'    => $classAfterUpdateStatus,
            'text_after_update'     => $textAfterUpdateStatus,
            'value_after_update'    => $valueAfterUpdateStatus,
            'class_current'         => $request['current_class'],
            'msg'                   => __('messages.update_status_success', ['table' => 'Slider', 'value' => $record->name]),
        ]);
    }

    public function updateOrdering(Request $request)
    {
        $request  = $request->all();
       
        // Setup the validator
        $rules = ['ordering' => 'required|integer'];
        $messages = [
                    'ordering.required'     => 'Vị trí không được để trống !',
                    'ordering.integer'     => 'Vị trí phải là số nguyên !',
        ];
        $validator = Validator::make($request, $rules, $messages);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'id'      => $request['id'],
                'errors'  => $validator->errors()->first()

            ], 422); // 400 being the HTTP code for an invalid request.
        }
        $record   = $this->sliderRepository
                     ->updateOrdering(Arr::only($request, ['id', 'ordering']));

        return response()->json([
            'success'  => true,
            'id'       => $record->id,
            'ordering' => $record->ordering,     
            'msg'      =>  __('messages.update_ordering_success', ['table' => 'Slider', 'value' => $record->name]),
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
                'msg' => 'Xóa thành công các id: ' . implode(', ', $ids),
            ]);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            DB::rollBack();
        }
        
        
    }
}
