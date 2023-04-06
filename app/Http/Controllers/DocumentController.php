<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Document;
use App\Models\Action;

class DocumentController extends Controller
{
    private $document;
    private $action;

    public function __construct(Document $document, Action $action)
    {
        $this->middleware('auth');
        
        $this->document = $document;
        $this->action = $action;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->document->query();

        if(isset($request->search)){
            $query->orWhere('title', 'LIKE', '%' . $request->search . '%');
        }

        if($request->has('action')){
            $query->orWhere('action_id', $request->action);
        }

        $documents = $query->orderBy('id','DESC')->paginate(10);
        $actions = $this->action->all();
        
        return view('admin.documents.index',['documents' => $documents, 'actions' => $actions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $actions = $this->action->all();
        return view('admin.documents.create',['actions' => $actions]);
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
            'action_id' => 'required',
            'file' => 'sometimes|required|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('file') && $request->file('file')->isValid())
        {
            $file = $request->file->store('models','public');
            $data['file'] = $file;
        }

        $document = $this->document->create($data);
        if($document)
        {
            return redirect('admin/document/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/document/create')->with('error', 'Erro ao inserir o registro!');
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

    /*
    public function download($slug)
    {
        $record = $this->model->where('slug',$slug)->first();
        if($record){
            if(Storage::exists($record['document'])){
                return Storage::download($record['document']);
            } else {
                return redirect('admin/models')->with('alert', 'Desculpe! Arquivo não encontrado.');
            }
        } else {
            return redirect('admin/models')->with('alert', 'Arquivo não existe!');
        }
    }
    */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $actions = $this->action->all();
        $document = $this->document->find($id);
        if($document){
            return view('admin.documents.edit',['document' => $document, 'actions' => $actions]);
        } else {
            return redirect('admin/documents')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->document->find($id);

        Validator::make($data, [
            'title' => 'required|string|min:3|max:100',
            'file' => 'sometimes|required|max:50000|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('file') && $request->file('file')->isValid())
        {
            if(Storage::disk('public')->exists($record['file'])){
                Storage::disk('public')->delete($record['file']);
            } 

            $new_file = $request->file->store('models','public');
            $data['file'] = $new_file;
        }

        if($record->update($data))
        {
            return redirect('admin/documents')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/documents')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->document->find($id);
        if($data->delete())
        {
            if( ($data['file'] != null) && (Storage::disk("public")->exists($data['file'])) ) {
                Storage::disk("public")->delete($data['file']);
            }

            return redirect('admin/documents')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/documents')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
