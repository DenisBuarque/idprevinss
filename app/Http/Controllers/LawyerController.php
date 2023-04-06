<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Lawyer;
use App\Models\User;

class LawyerController extends Controller
{
    private $lawyer;
    private $user;

    public function __construct(Lawyer $lawyer, User $user)
    {
        $this->middleware('auth');
        
        $this->lawyer = $lawyer;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $franchisees = $this->user->where('type','F')->get();

        // inicia a consulta
        $query = $this->lawyer->query();

        if ($request->has('franchisee')) {
            $query->orWhere('user_id', '=', $request->franchisee);
        }

        if (isset($request->search)) {
            $query->orWhere('name', 'LIKE', '%'.$request->search.'%');
        }

        $lawyers = $query->orderBy('id','DESC')->paginate(10);
        
        return view('admin.lawyers.index',[
            'franchisees' => $franchisees,
            'lawyers' => $lawyers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = $this->user->where('type','F')->get();
        return view('admin.lawyers.create',['users' => $users]);
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
            'name' => 'required|string|min:3|max:100',
            'oab' => 'required|string|max:20|min:3',
            'user_id' => 'required',
        ])->validate();

        // salva a imagem de existir
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $file = $request->image->store('lawyer','public');
            $data['image'] = $file;
        }

        $lawyer = $this->lawyer->create($data);
        if($lawyer)
        {
            return redirect('admin/lawyer/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/lawyer/create')->with('error', 'Erro ao inserir o registro!');
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
        $users = $this->user->where('type','F')->get();
        $lawyer = $this->lawyer->find($id);
        if($lawyer){
            return view('admin.lawyers.edit',['lawyer' => $lawyer, 'users' => $users]);
        } else {
            return redirect('admin/lawyers')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->lawyer->find($id);

        Validator::make($data, [
            'name' => 'required|string|min:3|max:100',
            'oab' => 'required|string|max:20|min:3',
            'user_id' => 'required',
        ])->validate();

        // atualiza a imagem
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            if($record['image'] != null){
                if(Storage::exists($record['image'])) {
                    Storage::delete($record['image']);
                }
            }
            
            $new_file = $request->image->store('lawyer','public');
            $data['image'] = $new_file;
        }

        if($record->update($data)){
            return redirect('admin/lawyers')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/lawyers')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->lawyer->find($id);
        if($data->delete()){

            if($data['image'] != null){
                if(Storage::exists($data['image'])){
                    Storage::delete($data['image']);
                }
            }

            return redirect('admin/lawyers')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/lawyers')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
