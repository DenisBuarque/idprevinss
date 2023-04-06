<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Training;

class TrainingController extends Controller
{
    private $training;

    public function __construct(Training $training) 
    {
        $this->middleware('auth');
        $this->training = $training;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->training->query();

        if(isset($request->search)){
            $query->orWhere('title', 'LIKE', '%' . $request->search . '%');
        }

        $trainings = $query->orderBy('id','DESC')->paginate(20);
        
        return view('admin.trainings.index',['trainings' => $trainings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.trainings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'title' => 'required|string|min:3|max:100',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,xlsm,xlsb,xltx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('file') && $request->file('file')->isValid())
        {
            $file = $request->file->store('trainings','public');
            $data['file'] = $file;
        }

        $training = $this->training->create($data);
        if($training){
            return redirect('admin/training/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/training/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $training = $this->training->find($id);
        if($training){
            return view('admin.trainings.edit',['training' => $training]);
        } else {
            return redirect('admin/trainings')->with('alert', 'Desculpe! Não encontramos o registro!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $record = $this->training->find($id);

        Validator::make($data, [
            'title' => 'required|string|min:3|max:100',
            'file' => 'sometimes|required|max:50000|mimes:pdf,doc,docx,xlsx,xlsm,xlsb,xltx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('file') && $request->file('file')->isValid())
        {
            if(Storage::disk('public')->exists($record['file'])){
                Storage::disk('public')->delete($record['file']);
            } 

            $new_file = $request->file->store('trainings','public');
            $data['file'] = $new_file;
        }

        if($record->update($data)) {
            return redirect('admin/trainings')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/trainings')->with('error', 'Erro ao alterar o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->training->find($id);
        if($data->delete())
        {
            if(Storage::disk("public")->exists($data['file'])) {
                Storage::disk("public")->delete($data['file']);
            }

            return redirect('admin/trainings')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/trainings')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
