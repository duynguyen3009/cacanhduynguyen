<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\CategoryRepository;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use DB;

class CategoryController extends AdminController
{
    protected $categoryRepository;
    protected $fieldsAcceptSearch   = ['id', 'name'];
    protected $fieldsAcceptOrdering = [ // show select để orderBy
        ''              => 'Sắp xếp',
        'id'            => 'Id', 
        'name'          => 'Tên', 
        'ordering'      => 'Vị trí',
    ];
    protected $defaultOrderBy = [
        'ordering'  => 'DESC', 
        'id'        => 'DESC'
    ];

    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $request                            = request()->all();
        $request['fieldsAcceptSearch']      = $this->fieldsAcceptSearch;
        $request['fieldsAcceptOrdering']    = $this->fieldsAcceptOrdering;
        $request['defaultOrderBy']          = $this->defaultOrderBy;

        $records = $this->categoryRepository->list($request);

        return view('admin.category.index', compact('records', 'request'));
    }

    public function form()
    {
        $formFields = request()->all();
        $record = null;

        //case edit
        if (isset($formFields['id'])) {
            $record = $this->categoryRepository->getRecord($formFields['id']);
        }

        $request = Arr::only($formFields, ['transform_search']);

        return view('admin.category.form', compact('request', 'record'));
    }

    public function store(CategoryRequest $request)
    {
        $formFields     = $request->all();
        
        $formFieldsAccept       = Arr::only($formFields, ['id', 'name', 'status', 'ordering']);

        //case edit
        if (isset($formFields['id'])) {
            $id = $this->categoryRepository->updateRecord($formFieldsAccept);
            session()->flash('action_success', __('messages.update_success', ['attribute' => $id]));
            
        } else {
            $this->categoryRepository->insert($formFieldsAccept);
            session()->flash('action_success', __('messages.insert_success', ['attribute' => $formFieldsAccept['name']]));
        }
        return response()->json([
            'success'   => true,
            'url'       => route('admin.category.index'),
        ]);

    }

    public function deleteData(Request $request)
    {
        $request    = $request->all();
        $ids        = array_keys($request);

        try {
            DB::beginTransaction();
            // delete database
            $record     = $this->categoryRepository->deleteData($ids);

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
