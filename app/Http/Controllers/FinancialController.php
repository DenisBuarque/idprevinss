<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Financial;
use App\Models\User;
use App\Models\Lead;
use App\Models\Action;
use App\Models\FinancialPhotos;
use App\Models\Installment;
use App\Models\FeedbackLead;

class FinancialController extends Controller
{
    private $financial;
    private $user;
    private $lead;
    private $action;
    private $feedback;

    public function __construct(Financial $financial, User $user, Lead $lead, Action $action, FeedbackLead $feedback)
    {
        $this->middleware('auth');

        $this->financial = $financial;
        $this->user = $user;
        $this->lead = $lead;
        $this->action = $action;
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

        $query = $this->financial->query();

        if ($request->has('user_id')) {
            $query->orWhere('user_id','=',$request->franchisee);
        }

        if($type_user == "A") {
            // consta registros
            $confirmation = $this->financial->where('confirmation','=','S')->count();
            $not_confirmation = $this->financial->where('confirmation','=','N')->count();
            $invoicing = $this->financial->where('confirmation','=','S')->sum('value_causa');
            $pending = $this->financial->where('confirmation','=','N')->sum('value_causa');
            // realisa a consulta
            $financials = $query->where('confirmation','N')->orderBy('id', 'DESC')->paginate(10);
        } else {
            // conta registros
            $confirmation = $this->financial->where('confirmation','=','S')->where('user_id',auth()->user()->id)->count();
            $not_confirmation = $this->financial->where('confirmation','=','N')->where('user_id',auth()->user()->id)->count();
            $invoicing = $this->financial->where('confirmation','=','S')->where('user_id',auth()->user()->id)->sum('value_causa');
            $pending = $this->financial->where('confirmation','=','N')->where('user_id',auth()->user()->id)->sum('value_causa');
            //realiza a consulta
            $financials = $query->where('confirmation','N')->where('user_id',auth()->user()->id)->orderBy('id', 'DESC')->paginate(10);
        }

        $join = $this->user->rightJoin('financials','users.id','=','financials.user_id')->select('users.id','users.name')->get();

        /*
        $users = DB::table('users')
            ->join('contacts', 'users.id', '=', 'contacts.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();
        */

        return view('admin.financials.index', [
            'confirmation' => $confirmation,
            'not_confirmation' => $not_confirmation,
            'invoicing' => $invoicing,
            'pending' => $pending,
            'franchisees' => $franchisees, 
            'financials' => $financials
        ]);
    }

    public function findos(Request $request)
    {
        $type_user = auth()->user()->type;
        $franchisees = $this->user->where('type','F')->get();

        $query = $this->financial->query();

        if ($request->has('user_id')) {
            $query->orWhere('user_id','=',$request->franchisee);
        }

        if($type_user == "A") {
            // consta registros
            $confirmation = $this->financial->where('confirmation','=','S')->count();
            $not_confirmation = $this->financial->where('confirmation','=','N')->count();
            $invoicing = $this->financial->where('confirmation','=','S')->sum('value_causa');
            $pending = $this->financial->where('confirmation','=','N')->sum('value_causa');
            // realisa a consulta
            $financials = $query->where('confirmation','=','S')->orderBy('id', 'DESC')->paginate(10);
        } else {
            // conta registros
            $confirmation = $this->financial->where('confirmation','=','S')->where('user_id',auth()->user()->id)->count();
            $not_confirmation = $this->financial->where('confirmation','=','N')->where('user_id',auth()->user()->id)->count();
            $invoicing = $this->financial->where('confirmation','=','S')->where('user_id',auth()->user()->id)->sum('value_causa');
            $pending = $this->financial->where('confirmation','=','N')->where('user_id',auth()->user()->id)->sum('value_causa');
            //realiza a consulta
            $financials = $query->where('confirmation','=','S')->where('user_id',auth()->user()->id)->orderBy('id', 'DESC')->paginate(10);
        }

        return view('admin.financials.findos', [
            'confirmation' => $confirmation,
            'not_confirmation' => $not_confirmation,
            'invoicing' => $invoicing,
            'pending' => $pending,
            'franchisees' => $franchisees, 
            'financials' => $financials
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $actions = $this->action->all();
        $lead = $this->lead->find($id);
        //$user = $this->lead->user();
        return view('admin.financial.create',[
            'actions' => $actions,
            'lead' => $lead
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function confirm(Request $request) 
    {
        $data = $request->all();

        Validator::make($data, [
            'image' => 'required|mimes:jpg,jpeg,gif,png',
        ])->validate();

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $file = $request->image->store('receipts','public');
            $data['image'] = $file;
        }

        $data['active'] = "S";
        $installment = \App\Models\Installment::find($request->installment_id);
        $installment->update($data);
        return redirect('admin/financial/edit/'.$request->financial_id)->with('success', 'Pagamento informado, aguarde a confirmação da matriz.');
    }

    public function pago ($id) {
        $data['active'] = "C";
        $installment = \App\Models\Installment::find($id);
        $installment->update($data);
        return redirect('admin/financial/edit/'.$installment->financial_id)->with('success', 'Pagamento confirmado pela matriz.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$actions = $this->action->all();
        //$lead = $this->lead->find($id);
        //$user = $this->lead->user();

        $financial = $this->financial->find($id);
        $feedbackLeads = $this->feedback->orderBy('id','DESC')->where('lead_id','=',$financial->lead_id)->get();
        
        if($financial)
        {
            return view('admin.financials.edit',['financial' => $financial, 'feedbacks' => $feedbackLeads]);
        } else {
            return redirect('admin/financial')->with('alert', 'Desculpe! Não encontramos o registro!');
        }
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
            return redirect('admin/financial/edit/'.$data['financial_id'])->with('success', 'Comentário adicionado com sucesso!');;
        } else {
            return redirect('admin/financials')->with('error', 'Erro ao inserido comentário financeiro!');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /*function calcularParcelas($nParcelas, $dataPrimeiraParcela = null){
        if($dataPrimeiraParcela != null){
            $dataPrimeiraParcela = explode( "/",$dataPrimeiraParcela);
            $dia = $dataPrimeiraParcela[0];
            $mes = $dataPrimeiraParcela[1];
            $ano = $dataPrimeiraParcela[2];
        } else {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
        }
        
        for($x = 0; $x < $nParcelas; $x++){
            return date("Y-m-d",strtotime("+".$x." month",mktime(0, 0, 0,$mes,$dia,$ano)));
        }
    }

    echo "Calcula as parcela a partir de hoje<br/>";
    calcularParcelas(5);
    echo "<br/><br/>";
    echo "Calcula as parcela a partir de uma data qualquer<br/>";
    calcularParcelas(5, "31/08/2011");
    */
    
    public function update(Request $request, $id)
    {
        $data = $request->all();

        Validator::make($data, [
            'receipt_date' => 'required|date',
            'bank' => 'required',
            'value_causa' => 'required|string',
            //'photos' => 'sometimes|required|mimes:jpg,jpeg,gif,png',
        ])->validate();

        $data['value_causa'] = str_replace(['.',','], ['','.'], $request->value_causa);
        $data['value_client'] = str_replace(['.',','], ['','.'], $request->value_client);
        $data['fees'] = str_replace(['.',','], ['','.'], $request->fees);
        if(isset($data['payment_amount'])){
            $data['payment_amount'] = str_replace(['.',','], ['','.'], $request->payment_amount);
        } else {
            $data['payment_amount'] = 0;
        }

        $record = $this->financial->find($id);
        if($record->update($data)){

            if($request->hasFile('photos'))
            {
                $images = $this->imageUpload($request,'image');
                $record->photos()->createMany($images);
            }

            // se existe uma data de pagamento
            if(isset($data['receipt_date'])) 
            {
                // verificar se já foi gerado as parcelas de pagamento do financeiro
                $installment_exists = \App\Models\Installment::where('financial_id',$id)->count();
                if($installment_exists == 0) 
                {
                    //divide o valor pelo total de pacelas
                    $value_installment = $data['value_causa'] / $data['installments'];
                    // pega a data de pagamento
                    $dataPrimeiraParcela = \Carbon\Carbon::parse($data['receipt_date'])->format('d/m/Y');
    
                    if($dataPrimeiraParcela != null) 
                    {
                        $dataPrimeiraParcela = explode( "/",$dataPrimeiraParcela);
                        $dia = $dataPrimeiraParcela[0];
                        $ano = $dataPrimeiraParcela[2];
                        $mes = $dataPrimeiraParcela[1];
                    } else { // se o $dataPrimeiraParcela for null pega a data atual
                        $dia = date("d");
                        $mes = date("m");
                        $ano = date("Y");
                    }
                    
                    for($i = 0; $i < $data['installments']; $i++){
                        $record->installments()->create([
                            'user_id' => $data['user_id'],
                            'financial_id' => $id,
                            'value' => $value_installment,
                            'date' => date("Y-m-d",strtotime("+".$i." month",mktime(0, 0, 0,$mes,$dia,$ano))),
                            'active' => 'N',
                        ]);
                    }
                }
            }

            return redirect('admin/financials')->with('success', 'Registro atualizado com sucesso!');

        } else {
            return redirect('admin/financials')->with('error', 'Erro ao atualizar o registro!');
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
        $data = \App\Models\Installment::find($id);
        if($data->delete())
        {
            return redirect('admin/financial/edit/'.$data['financial_id'])->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/financials')->with('alert', 'Erro ao excluir o registro!');
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

    // remove a imagem do produto
    public function remove(Request $request)
    {
        $photo = $request->get('photo');

        if(Storage::disk('public')->exists($photo)){
            Storage::disk('public')->delete($photo);
        }

        $removePhoto = FinancialPhotos::where('image', $photo);
        $financial_id = $removePhoto->first()->financial_id;

        $removePhoto->delete();

        return redirect()->route('admin.financials.edit',['id' => $financial_id]); 
    }
}
