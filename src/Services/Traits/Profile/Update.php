<?php

namespace TomatoPHP\TomatoCrm\Services\Traits\Profile;

use Illuminate\Support\Facades\Schema;
use TomatoPHP\TomatoAdmin\Helpers\ApiResponse;
use TomatoPHP\TomatoCRM\Helpers\Response;

trait Update
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\Illuminate\Http\Request $request, array $validation=[], ?string $resource=null): \Illuminate\Http\JsonResponse
    {

        $user = $request->user();

        $validationArray = array_merge($this->updateValidation, $validation);
        foreach ($validationArray as $key=>$item){
            if(str_contains($item, 'unique')){
                $validationArray[$key].=',id,'.$user->id;
            }
        }

        $request->validate($validationArray);

        $getUserModel = $this->model::find($user->id);
        $data = $request->all();

        $meta = [];
        foreach ($data as $key=>$value){
            $onTable = Schema::hasColumn(app($this->model)->getTable(), $key);
            if(!$onTable){
                $meta[$key] = $value;
                unset($data[$key]);
            }
        }

        $getUserModel->update($data);

        foreach ($meta as $key=>$item){
            $getUserModel->meta($key, $item);
        }

        if($this->resource){
            $getUserModel = $this->resource::make($getUserModel);
        }

        return ApiResponse::data( $getUserModel, __("Profile Data Updated"));
    }
}
