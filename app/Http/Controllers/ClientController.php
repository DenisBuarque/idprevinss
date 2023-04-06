<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Lead;
use App\Models\User;
use App\Models\FeedbackLead;
use App\Models\Term;
use App\Models\ClientPhotos;
use App\Models\Lawyer;
use App\Models\Action;
use App\Models\Financial;
use App\Models\Installment;
use App\Models\Document;

class ClientController extends Controller
{
    private $lead;
    private $user;
    private $feedback;
    private $term;
    private $clientPhotos;
    private $lawyer;
    private $action;
    private $installment;
    private $document;

    public function __construct (
        Lead $lead, 
        User $user, 
        FeedbackLead $feedback, 
        Term $term, 
        ClientPhotos $clientPhotos, 
        Lawyer $lawyer,
        Action $action,
        Financial $financial,
        Installment $installment,
        Document $document
        )
    {   
        $this->middleware('auth');
        
        $this->lead = $lead;
        $this->user = $user;
        $this->feedback = $feedback;
        $this->term = $term;
        $this->lawyer = $lawyer;
        $this->clientPhotos = $clientPhotos;
        $this->action = $action;
        $this->financial = $financial;
        $this->installment = $installment;
        $this->document = $document;
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
        $installments = $this->installment->all();
        $terms = $this->term->all();

        // Consulta o Model
        $query = $this->lead->query(); 

        if ($request->has('franchisee')) {
            $query->orWhere('user_id', '=', $request->franchisee)->whereIn('tag',[3]);
        }

        if ($request->has('situation')) {
            $query->orWhere('situation', '=', $request->situation)->whereIn('tag',[3]);
        }

        if (isset($request->search)) {
            $columns = ['name','phone','email','address','district','city','state'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->search . '%')->whereIn('tag',[3]);
            }
        }

        // Monta a lista de clientes leads
        if($type_user == "A"){
            // conta os registros
            $totals = $this->lead->whereIn('situation',[1,2,4,5])->where('tag',3)->get()->count();
            $waiting = $this->lead->where('tag',2)->get()->count();
            $converted_lead = $this->lead->where('tag',3)->get()->count();
            $unconverted_lead = $this->lead->where('tag',4)->get()->count();
            $procedente = $this->lead->where('situation',3)->where('tag',3)->where('situation',3)->get()->count();
            $improcedente = $this->lead->where('tag',3)->where('situation',4)->get()->count();
            $resources = $this->lead->where('tag',3)->where('situation',5)->get()->count();
            $findos = $this->financial->where('confirmation','S')->get()->count();
            // consulta os registros
            $leads = $query->whereIn('situation',[1,2,4,5])->where('tag',3)->orderBy('id','DESC')->paginate(10);
        }else {
            // conta os registros
            $totals = $this->lead->where('user_id',auth()->user()->id)->whereIn('situation',[1,2,4,5])->where('tag',3)->get()->count();
            $waiting = $this->lead->where('user_id',auth()->user()->id)->where('tag',2)->get()->count();
            $converted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag',3)->get()->count();
            $unconverted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag',4)->get()->count();
            $procedente = $this->lead->where('user_id',auth()->user()->id)->where('situation',3)->where('tag',3)->get()->count();
            $improcedente = $this->lead->where('user_id',auth()->user()->id)->where('situation',4)->where('tag',3)->get()->count();
            $resources = $this->lead->where('user_id',auth()->user()->id)->where('situation',5)->where('tag',3)->get()->count();
            $findos = $this->financial->where('user_id',auth()->user()->id)->where('confirmation','S')->get()->count();
            // consulta os registros
            $leads = $query->whereIn('situation',[1,2,4,5])->where('user_id',auth()->user()->id)->where('tag',3)->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.clients.index',[
            'totals' => $totals, 
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead,
            'procedente' => $procedente,
            'improcedente' => $improcedente,
            'resources' => $resources,
            'franchisees' => $franchisees,
            'terms' => $terms,
            'installments' => $installments,
            'findos' => $findos,
            'leads' => $leads
        ]);
    }

    public function tag($tag)
    {
        $type_user = auth()->user()->type;

        // consulta o model
        $query = $this->lead->query(); 
        
        if($type_user == "A"){
            // conta os registros
            $waiting = $this->lead->where('tag',2)->get()->count();
            $converted_lead = $this->lead->where('tag',3)->get()->count();
            $unconverted_lead = $this->lead->where('tag',4)->get()->count();
            $procedente = $this->lead->where('tag',3)->where('situation',3)->get()->count();
            $improcedente = $this->lead->where('tag',3)->where('situation',4)->get()->count();
            $resources = $this->lead->where('tag',3)->where('situation',5)->get()->count();
            $findos = $this->financial->where('confirmation','S')->get()->count();
            // consulta os registros
            $leads = $query->whereIn('situation',[1,2,3,4,5])->where('tag',$tag)->orderBy('id','DESC')->paginate(10);
        }else {
            // conta os registros
            $waiting = $this->lead->where('user_id',auth()->user()->id)->where('tag',2)->get()->count();
            $converted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag',3)->get()->count();
            $unconverted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag',4)->get()->count();
            $procedente = $this->lead->where('user_id',auth()->user()->id)->where('situation',3)->where('tag','3')->get()->count();
            $improcedente = $this->lead->where('user_id',auth()->user()->id)->where('situation',4)->where('tag','3')->get()->count();
            $resources = $this->lead->where('user_id',auth()->user()->id)->where('situation',5)->where('tag','3')->get()->count();
            $findos = $this->financial->where('user_id',auth()->user()->id)->where('confirmation','S')->get()->count();
            // consulta os registros
            $leads = $query->whereIn('situation',[1,2,3,4,5])->where('user_id',auth()->user()->id)->where('tag',$tag)->orderBy('id','DESC')->paginate(10);
        }

        $franchisees = $this->user->where('type','F')->get();
        $terms = $this->term->all();
        $installments = $this->installment->all();

        return view('admin.clients.tag',[
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead,
            'procedente' => $procedente,
            'improcedente' => $improcedente,
            'resources' => $resources,
            'franchisees' => $franchisees,
            'terms' => $terms,
            'tag' => $tag,
            'leads' => $leads,
            'installments' => $installments,
            'findos' => $findos
        ]);
    }
    
    public function lawyers($id)
    {
        $lawyers = $this->lawyer->where('user_id',$id)->get();
        return view('admin.clients.lawyers',['lawyers' => $lawyers]);
    }

    public function documents($id)
    {
        $documents = $this->document->where('action_id',$id)->get();
        return view('admin.clients.documents',['documents' => $documents]);
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
            return redirect('admin/client/show/'.$data['lead_id'])->with('success', 'Comentário adicionado com sucesso!');;
        } else {
            return redirect('admin/clients')->with('error', 'Erro ao inserido o ticket!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $actions = $this->action->all();
        $users = $this->user->where('type','F')->get();
        return view('admin.clients.create',['users' => $users,'actions' => $actions]);
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

        $data['action'] = 1;

        Validator::make($data, [
            'name' => 'required|string|min:3',
            'phone' => 'required|string',
            'user_id' => 'required',
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
            'user_id' => 'required',
            //'action' => 'required',
        ])->validate();

        if(isset($data['financial'])):
            $data['financial'] = str_replace(['.', ','], ['', '.'], $data['financial']);
        else:
            $data['financial'] = 0;
        endif;

        if($request->has('confirmed')){
            $data['confirmed'] = true;
        } else {
            $data['confirmed'] = false;
        }

        $lead = $this->lead->create($data);
        if($lead)
        {
            if(isset($data['comments'])){
                $lead->feedbackLeads()->create([
                    'comments' => $data['comments'],
                    'user_id' => $data['user_id']
                ]);
            }
            
            if($request->hasFile('photos')){
                $images = $this->imageUpload($request,'image');
                $lead->photos()->createMany($images);
            }

            if(isset($data['lawyer']) && count($data['lawyer']))
            {
                foreach($data['lawyer'] as $key => $value):
                    $lead->lawyers()->attach($value);
                endforeach;
            }

            // cria o financeiro se o cliento for convertido
            if($data['situation'] == 3) 
            {
                $lead_exists = $this->financial->where('lead_id',$lead->id)->count();
                if($lead_exists == 0) {
                    $lead->financial()->create([
                        'user_id' => $data['user_id'],
                        'lead_id' => $lead->id,
                        'confirmation' => 'N'
                    ]);
                }
            }

            return redirect('admin/clients')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/client/create')->with('error', 'Erro ao inserir o registro!');
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
        $users = $this->user->all();
        $actions = $this->action->all();
        $lead = $this->lead->find($id);
        $feedbackLeads = $this->feedback->orderBy('id','DESC')->where('lead_id','=',$id)->get();
        if($lead){
            return view('admin.clients.show',[
                'lead' => $lead, 
                'users' => $users, 
                'feedbackLeads' => $feedbackLeads, 
                'actions' => $actions
            ]);
        } else {
            return redirect('admin/clients')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $actions = $this->action->all();
        $documents = $this->document->all();
        $lawyers = $this->lawyer->all();
        
        $lead = $this->lead->find($id);

        $user = $this->user->where('id',$lead->user_id)->first();


        if($lead){
            return view('admin.clients.edit',[
                'lead' => $lead,
                'user' => $user,
                'lawyers' => $lawyers,
                'actions' => $actions,
                'documents' => $documents
                ]
            );
        } else {
            return redirect('admin/clients')->with('alert', 'Desculpe! Não encontramos o registro!');
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
            'user_id' => 'required',
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
            'action' => 'required',
        ])->validate();

        if(isset($data['financial'])):
            $data['financial'] = str_replace(['.', ','], ['', '.'], $data['financial']);
        else:
            $data['financial'] = 0;
        endif;

        if($request->has('confirmed')){
            $data['confirmed'] = true;
        } else {
            $data['confirmed'] = false;
        }

        // atualiza os advogados
        $permissions = $record->lawyers;
        if(count($permissions)){
            foreach($permissions as $key => $value):
                $record->lawyers()->detach($value->id);
            endforeach;
        }

        if(isset($data['lawyer']) && count($data['lawyer']))
        {
            foreach($data['lawyer'] as $key => $value):
                $record->lawyers()->attach($value);
            endforeach;
        }

        if($record->update($data)) {

            if(isset($data['comments'])) 
            {
                $record->feedbackLeads()->create([
                    'comments' => $data['comments'],
                    'user_id' => $data['user_id'],
                ]);
            }

            // se o cliente estiver convertido procedente.
            if($data['situation'] == 3) 
            {
                $lead_exists = $this->financial->where('lead_id',$id)->count();
                if($lead_exists == 0) {
                    $record->financial()->create([
                        'user_id' => $data['user_id'],
                        'lead_id' => $id,
                        'confirmation' => 'N'
                    ]);
                }
            }

            if($request->hasFile('photos'))
            {
                $images = $this->imageUpload($request,'image');
                $record->photos()->createMany($images);
            }

            return redirect('admin/clients')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/clients')->with('error', 'Erro ao alterar o registro!');
        }
    }

    public function update_term(Request $request, $id)
    {
        $data = $request->all();
        $record = $this->lead->find($id);

        Validator::make($data, [
            'responsible' => 'required|string|min:3|max:50',
            'date_fulfilled' => 'required',
            'greeting' => 'required|string|min:10',
        ])->validate();

        if($record->update($data)):
            return redirect('admin/clients/term')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/clients/term')->with('error', 'Erro ao alterar o registro!');
        endif;
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
        $photos = $this->clientPhotos->where('lead_id', $id)->get();
        
        if($data->delete()) {

            foreach($photos as $photo){
                $photo->delete();
                if(Storage::disk('public')->exists($photo->image)){
                    Storage::disk('public')->delete($photo->image);
                }
            }

            return redirect('admin/clients')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/clients')->with('error', 'Erro ao excluir o registro!');
        }
    }

    // realiza o upload da imagem do produto
    private function imageUpload(Request $request, $imageColumn)
    {
        $images = $request->file('photos');
        $uploadedImage = [];
        foreach($images as $image){
            $uploadedImage[] = [$imageColumn => $image->store('documents','public')];
        }
        return $uploadedImage;
    }

    // remove a imagem
    public function remove(Request $request)
    {
       $photo = $request->get('photo');

        if(Storage::disk('public')->exists($photo)){
            Storage::disk('public')->delete($photo);
        }

        $removePhoto = ClientPhotos::where('image', $photo);
        $lead_id = $removePhoto->first()->lead_id;

        $removePhoto->delete();

        return redirect()->route('admin.clients.edit',['id' => $lead_id]); 
    }
}
