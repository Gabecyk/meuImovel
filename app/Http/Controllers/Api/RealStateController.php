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
        $realState = $this->realState->paginate('10');

        return response()->json($realState, 200);
    }

    public function show($id)
    {
        try {
            $realState = $this->realState->findOrFail($id);


            return response()->json($realState);
            
        } catch (\Exception $ex) {
            $message = new ApiMessages($ex->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        dd($request->file('images'));
        try {

            $realState = $this->realState->create($data); // Mass Asignment

            if(isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
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

    public function update(RealStateRequest $request, $id)
    {
        $data = $request->all();

        try {
            $realState = $this->realState->findOrFail($id);

            /*if(!$realState){
                return response()->json([
                    'error' => "Imóvel não encontrado"
                ], 404);
            }*/

            $realState->update($data);

            if(isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
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
            $realState = $this->realState->findOrFail($id);
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
