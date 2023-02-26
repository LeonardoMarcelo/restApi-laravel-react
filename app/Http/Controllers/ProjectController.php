<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{

    public function index()
    {
        //
        $getAll = Project::all();

        $data = [
            'status' => 200,
            'projects' => $getAll
        ];

        if ($getAll->count() > 0) {
            return response()->json($data, 200);
        }
        return response()->json([
            'message' => 'Nenhum projeto encontrado',
            'status' => 422,
        ], 422);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:191',
            'cliente' => 'required|max:191',
            'budget' => 'required|max:191'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'error' => $validator->messages()
            ], 404);
        }
        $project = new Project();
        $project->nome = $request->nome;
        $project->cliente = $request->cliente;
        $project->status = $request->status;
        $project->budget = $request->budget;
        $project->data = $request->data;
        $project->deadline = $request->deadline;

        if ($request->data > $request->deadline) {
            $project->situation = 'Estado critíco';
        } else if ($request->data == $request->deadline) {
            $project->situation = 'Em atenção';
        } else {
            $project->situation = 'Em dia';
        }

        $create = $project->save();

        if (!$create) {
            return response()->json([
                'status' => 422,
                'msg' => 'Erro ao criar'
            ], 422);
        }

        return response()->json([
            'status' => 200,
            'projects' => $project,
            'msg' => 'Criado com sucesso'
        ], 200);
    }


    public function show(Request $request)
    {
        //
        $product = Project::find($request->id);

        if (!$product) {
            return  response()->json([
                'status' => 422,
                'message' => 'Project not found'
            ], 422);
        }

        $data = ['data' => $product];
        return response()->json($data);
    }


    public function edit(Project $project)
    {
        //
    }


    public function update(Request $request)
    {

        $data = $request->all();
        $update = Project::find($request->id);

        if (!$update) {
            return  response()->json([
                'status' => 422,
                'message' => 'Error,tente novamente mais tarde!'
            ], 422);
        }

        $update->update($data);
        return response()->json($data, 200);
    }


    public function destroy(Request $request)
    {
        $delete = Project::find($request->id);
        if (!$delete) {
            return  response()->json([
                'status' => 422,
                'message' => 'Error,tente novamente mais tarde!'
            ], 422);
        }

        $delete->delete();
        return  response()->json([
            'status' => 200,
            'message' => 'Project deletado com sucesso!'
        ], 200);
    }
}
