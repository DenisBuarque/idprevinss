<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Worksheet;

class WorksheetController extends Controller
{
    private $worksheet;

    public function __construct(Worksheet $worksheet) 
    {
        $this->middleware('auth');
        $this->worksheet = $worksheet;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->worksheet->query();

        if(isset($request->search)){
            $query->orWhere('title', 'LIKE', '%' . $request->search . '%');
        }

        $worksheets = $query->orderBy('id','DESC')->paginate(10);
        
        return view('admin.worksheets.index',['worksheets' => $worksheets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.worksheets.create');
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
            $file = $request->file->store('worksheets','public');
            $data['file'] = $file;
        }

        $worksheet = $this->worksheet->create($data);
        if($worksheet){
            return redirect('admin/worksheet/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/worksheet/create')->with('error', 'Erro ao inserir o registro!');
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
        $worksheet = $this->worksheet->find($id);
        if($worksheet){
            return view('admin.worksheets.edit',['worksheet' => $worksheet]);
        } else {
            return redirect('admin/worksheets')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->worksheet->find($id);

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

            $new_file = $request->file->store('worksheets','public');
            $data['file'] = $new_file;
        }

        if($record->update($data)) {
            return redirect('admin/worksheets')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/worksheets')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->worksheet->find($id);
        if($data->delete())
        {
            if(Storage::disk("public")->exists($data['file'])) {
                Storage::disk("public")->delete($data['file']);
            }

            return redirect('admin/worksheets')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/worksheets')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
