<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Lead;
use App\Models\User;
use App\Models\FeedbackLead;

class LeadController extends Controller
{
    private $lead;
    private $user;
    private $feedback;

    public function __construct(Lead $lead, User $user, FeedbackLead $feedback)
    {   
        $this->middleware('auth');
        $this->lead = $lead;
        $this->user = $user;
        $this->feedback = $feedback;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type_user = auth()->user()->type;
        $franchisees = $this->user->where('type','F')->get();
        // consulta o Model
        $query = $this->lead->query(); 

        if ($request->has('franchisee')) 
        {
            $query->orWhere('user_id', '=', $request->franchisee);
        }

        if (isset($request->search)) 
        {
            $columns = ['name','address','district','city','state'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->search . '%')->whereIn('tag',[1,2]);
            }
        } 

        if($type_user == "A") {
            // conta os  registros
            $leads_total = $this->lead->whereIn('tag',[1,2])->count();
            $waiting = $this->lead->where('tag','2')->get()->count();
            $converted_lead = $this->lead->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('tag','4')->get()->count();
            // lista os registros
            $leads = $query->whereIn('tag',[1,2])->orderBy('id','DESC')->paginate(10);

        } else { 
            // conta os  registros
            $leads_total = $this->lead->where('user_id',auth()->user()->id)->whereIn('tag',[1,2])->count();
            $waiting = $this->lead->where('user_id',auth()->user()->id)->where('tag','2')->get()->count();
            $converted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','4')->get()->count();
            // lista os registros
            $leads = $query->whereIn('tag',[1,2])->where('user_id',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
        }

        return view('admin.leads.index',[
            'leads_total' => $leads_total,
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead, 
            'franchisees' => $franchisees,
            'leads' => $leads, 
        ]);
    }

    public function leads($tag)
    {
        $type_user = auth()->user()->type;
        $franchisees = $this->user->where('type','F')->get();

        if($type_user == "A") {
            // conta os  registros
            $leads_total = $this->lead->where('tag',$tag)->count();
            $waiting = $this->lead->where('tag','2')->get()->count();
            $converted_lead = $this->lead->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('tag','4')->get()->count();
            // lista os registros
            $leads = $this->lead->where('tag',$tag)->orderBy('id','DESC')->paginate(10);

        } else { 
            // conta os  registros
            $leads_total = $this->lead->where('user_id',auth()->user()->id)->where('tag',$tag)->count();
            $waiting = $this->lead->where('user_id',auth()->user()->id)->where('tag','2')->get()->count();
            $converted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','4')->get()->count();
            // lista os registros
            $leads = $this->lead->where('tag',$tag)->where('user_id',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.leads.tag',[
            'leads_total' => $leads_total,
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead,
            'leads' => $leads,
            'franchisees' => $franchisees
        ]);
    }

    public function feedback(Request $request)
    {
        $data = $request->all();
        
        Validator::make($data, [
            'comments' => 'required|string',
        ])->validate();

        $data['user_id'] = auth()->user()->id;

        $create = $this->feedback->create($data);
        if ($create) {
            return redirect('admin/lead/show/'.$data['lead_id'])->with('success', 'Comentário adicionado com sucesso!');
        } else {
            return redirect('admin/leads')->with('error', 'Erro ao inserido o ticket!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = $this->user->where('type','F')->get();
        return view('admin.leads.create',['users' => $users]);
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
            'name' => 'required|string|min:3',
            'phone' => 'required|string',
            'user_id' => 'required',
        ])->validate();

        if($data['tag'] == 3){
            Validator::make($data, [
                'zip_code' => 'required|string|max:9',
                'address' => 'required|string|max:255',
                'number' => 'required|string|max:5',
                'district' => 'required|string|max:50',
                'city' => 'required|string|max:50',
                'state' => 'required|string|max:2',
            ])->validate();
        }

        $data['financial'] = 0;

        $lead = $this->lead->create($data);
        if($lead)
        {
            if(isset($data['comments'])){
                $lead->feedbackLeads()->create([
                    'comments' => $data['comments'],
                    'user_id' => auth()->user()->id,
                ]);
            }

            return redirect('admin/leads')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/lead/create')->with('error', 'Erro ao inserir o registro!');
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
        $users = \App\Models\User::all();
        $feedbackLeads = $this->feedback->orderBy('id','DESC')->where('lead_id',$id)->get();

        $lead = $this->lead->find($id);
        if($lead){
            return view('admin.leads.show',['lead' => $lead, 'users' => $users, 'feedbackLeads' => $feedbackLeads]);
        } else {
            return redirect('admin/leads')->with('alert', 'Desculpe! Não encontramos o registro!');
        }
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
        $lead = $this->lead->find($id);
        if($lead){
            return view('admin.leads.edit',[
                    'lead' => $lead, 
                    'users' => $users
                ]
            );
        } else {
            return redirect('admin/leads')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->lead->find($id);

        Validator::make($data, [
            'name' => 'required|string|min:3',
            'phone' => 'required|string',
        ])->validate();

        if($data['tag'] == 3){
            Validator::make($data, [
                'zip_code' => 'required|string|max:9',
                'address' => 'required|string|max:255',
                'number' => 'required|string|max:5',
                'district' => 'required|string|max:50',
                'city' => 'required|string|max:50',
                'state' => 'required|string|max:2',
            ])->validate();
        }

        $data['financial'] = 0;

        if($record->update($data))
        {
            if(isset($data['comments'])){
                $record->feedbackLeads()->create([
                    'comments' => $data['comments'],
                    'user_id' => auth()->user()->id,
                ]);
            }
            return redirect('admin/leads')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/leads')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->lead->find($id);
        if($data->delete()){
            return redirect('admin/leads')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/leads')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
