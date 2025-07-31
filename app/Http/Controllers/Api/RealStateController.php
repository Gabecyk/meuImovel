<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $realState = auth('api')->user()->real_state();
        
        //$realState = $this->realState->paginate('10');

        return response()->json($realState->paginate('10'), 200);
    }

    public function show($id)
    {
        try {
            //$realState = $this->realState->with('photos')->with('category_id')->findOrFail($id);
            $realState = auth('api')->user()->real_state()->with('photos')->with('categories')->findOrFail($id);

            return response()->json($realState);
            
        } catch (\Exception $ex) {
            $message = new ApiMessages($ex->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        $images = $request->file('images');

        try {
            $data['user_id'] = auth('api')->user()->id; //Busca o id do user usando token

            $realState = $this->realState->create($data); // Mass Asignment

            if(isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            if($images){
                $imagesUploded = [];

                foreach($images as $image){
                    $path = $image->store('images', 'public');
                    $imagesUploded[] = ['photo' => $path, 'is_thumb' => false];
                }
                $realState->photos()->createMany($imagesUploded);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel cadastrado com sucesso!'
                ]
            ], 200);
        } catch (\Exception $ex) {
            $message = new ApiMessages($ex->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update(RealStateRequest $request, $id) // cuidado ao fazer put no postman, tem enviar como post e adicionar campo '_method' e valor 'put'
    {
        $data = $request->all();
        $images = $request->file('images');

        try {
            //$realState = $this->realState->findOrFail($id);
            $realState = auth('api')->user()->real_state()->findOrFail($id);

            /*if(!$realState){
                return response()->json([
                    'error' => "Imóvel não encontrado"
                ], 404);
            }*/

            $realState->update($data);

            if(isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            if($images){
                $imagesUploded = [];

                foreach($images as $image){
                    $path = $image->store('images', 'public');
                    $imagesUploded[] = ['photo' => $path, 'is_thumb' => false];
                }
                $realState->photos()->createMany($imagesUploded);
            }

            return response()->json([
                'msg' => 'Imóvel atualizado com sucesso'
            ], 200);

        } catch (\Exception $ex) {
            $message = new ApiMessages($ex->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try {
            //$realState = $this->realState->findOrFail($id);
            $realState = auth('api')->user()->real_state()->findOrFail($id);
            $realState->delete();

            return response()->json([
                'msg' => 'deletado com sucesso!'
            ], 200);
            
        } catch (\Exception $ex) {
            $message = new ApiMessages($ex->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
