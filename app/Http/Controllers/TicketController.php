<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\FeedbackTicket;
use App\Models\Ticket;
use Carbon\Carbon;

class TicketController extends Controller
{
    private $ticket;
    private $user;
    private $feedback;

    public function __construct(Ticket $ticket, User $user, FeedbackTicket $feedback)
    {
        $this->middleware('auth');
        
        $this->ticket = $ticket;
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

        $query = $this->ticket->query(); // consulta o objeto

        if ($request->has('franchisee')) {
            $query->orWhere('user_id',$request->franchisee);
        }

        if($type_user == 'A') {
            // conta registros
            $open = $this->ticket->where('status','1')->get()->count();
            $resolved = $this->ticket->where('status','2')->get()->count();
            $pending = $this->ticket->where('status','3')->get()->count();
            //tickets
            $tickets = $query->orderBy('id','DESC')->whereIn('status',[1,3])->paginate(10);
        } else {
            // conta registros
            $open = $this->ticket->where('user_id',auth()->user()->id)->where('status','1')->get()->count();
            $resolved = $this->ticket->where('user_id',auth()->user()->id)->where('status','2')->get()->count();
            $pending = $this->ticket->where('user_id',auth()->user()->id)->where('status','3')->get()->count();
            //tickets
            $tickets = $query->where('user_id',auth()->user()->id)->whereIn('status',[1,3])->orderBy('id','DESC')->paginate(10);
        }

        return view('admin.tickets.index', [
            'open' => $open,
            'resolved' => $resolved,
            'pending' => $pending,
            'franchisees' => $franchisees,
            'tickets' => $tickets
        ]);
    }

    public function category($id)
    {
        $type_user = auth()->user()->type;
        $franchisees = $this->user->where('type','F')->get();

        $query = $this->ticket->query(); // consulta o objeto

        if($type_user == 'A') {
            // conta registros
            $open = $this->ticket->where('status','1')->get()->count();
            $resolved = $this->ticket->where('status','2')->get()->count();
            $pending = $this->ticket->where('status','3')->get()->count();
            //tickets
            $tickets = $query->orderBy('id','DESC')->where('status',$id)->paginate(10);
        } else {
            // conta registros
            $open = $this->ticket->where('user_id',auth()->user()->id)->where('status','1')->get()->count();
            $resolved = $this->ticket->where('user_id',auth()->user()->id)->where('status','2')->get()->count();
            $pending = $this->ticket->where('user_id',auth()->user()->id)->where('status','3')->get()->count();
            //tickets
            $tickets = $query->where('user_id',auth()->user()->id)->where('status',$id)->orderBy('id','DESC')->paginate(10);
        }

        return view('admin.tickets.category', [
            'open' => $open,
            'resolved' => $resolved,
            'pending' => $pending,
            'franchisees' => $franchisees,
            'tickets' => $tickets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tickets.create');
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
            'comments' => 'required|string|min:10',
        ])->validate();

        $data['user_id'] = auth()->user()->id;
        $data['code'] = Carbon::now()->timestamp; //timestamp em números
        $data['status'] = 1; // aberto

        $create = $this->ticket->create($data);
        if ($create) {

            /*if(isset($data['description'])){
                $create->feedbackTickets()->create([
                    'description' => $data['description'],
                    'user_id' => auth()->user()->id
                ]);
            }*/

            return redirect('admin/tickets')->with('success', 'Seu ticket foi enviado, aguarde resposta!');
        } else {
            return redirect('admin/tickets/create')->with('error', 'Erro ao inserido o ticket!');
        }
    }

    public function feedback(Request $request, $id)
    {
        $data = $request->all();
        Validator::make($data, [
            'description' => 'required|string|min:2',
        ])->validate();

        $data['user_id'] = auth()->user()->id;
        $data['ticket_id'] = $id;

        $create = $this->feedback->create($data);
        if ($create) {
            // atualiza o estado do ticket para pendente
            $pendente = $this->ticket->find($id);
            $status['status'] = 3;
            $pendente->update($status);

            //return redirect('admin/ticket/response/'.$data['ticket_id'])->with('success', 'Seu ticket foi enviado, aguardo sua resposta!');;
            return redirect('admin/tickets')->with('success', 'Seu ticket foi enviado, aguardo sua resposta!');
        } else {
            return redirect('admin/tickets')->with('error', 'Erro ao inserido o ticket!');
        }
    }

    /*public function response($id)
    {
        $users = \App\Models\User::all();
        $ticket = $this->ticket->find($id);
        $feedbacks = $this->feedback->orderBy('id','DESC')->where('ticket_id','=',$id)->get();
        return view('admin.tickets.response', ['ticket' => $ticket, 'users' => $users, 'feedbacks' => $feedbacks]);
    }

    */

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
        $ticket = $this->ticket->find($id);
        if($ticket){
            return view('admin.tickets.edit',['ticket' => $ticket]);
        } else {
            return redirect('admin/tickets')->with('alert', 'Erro, não encontrado o ticket que procura!');
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
        $record = $this->ticket->findOrFail($id);

        if($record->update($data)):

            if(isset($data['description'])){
                $record->feedbackTickets()->create([
                    'description' => $data['description'],
                    'user_id' => auth()->user()->id
                ]);
            }

            return redirect('admin/tickets')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/tickets')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->ticket->find($id);
        if($data->delete()) {
            return redirect('admin/tickets')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/tickets')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
