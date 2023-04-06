<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Installment;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Term;
use App\Models\User;
use App\Models\Administrative;

class HomeController extends Controller
{
    private $lead;
    private $installment;
    private $event;
    private $ticket;
    private $term;
    private $user;
    private $administrative;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, Installment $installment, Event $event, Ticket $ticket, Term $term, User $user, Administrative $administrative)
    {
        $this->middleware('auth');
        $this->lead = $lead;
        $this->installment = $installment;
        $this->event = $event;
        $this->ticket = $ticket;
        $this->term = $term;
        $this->user = $user;
        $this->administrative = $administrative;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $type_user = auth()->user()->type;

        if($type_user == 'A')
        {
            $leads_waiting = $this->lead->where('tag',2)->count();
            $leads_converted = $this->lead->where('tag',3)->count();
            $leads_unconverted = $this->lead->where('tag',4)->count();
            $procedente = $this->lead->where('tag','3')->where('situation','3')->get()->count();
            $improcedente = $this->lead->where('tag','3')->where('situation','4')->get()->count();
            $resources = $this->lead->where('tag','3')->where('situation','5')->get()->count();
            
            $installments = $this->installment->all();
            $administratives = $this->administrative->where('reminder',1)->get();
            $events = $this->event->limit(1)->inRandomOrder()->get();
            $tickets = $this->ticket->whereIn('status',[1,2])->get();
            $terms = $this->term->all();
            $reminders = $this->lead->where('confirmed',1)->get();
            $users = $this->user->where('type','F')->get();

            $leads = $this->lead->where('tag',1)->orderBy('id','DESC')->get();

        } else {
            $leads_waiting = $this->lead->where('user_id',auth()->user()->id)->where('tag',2)->count();
            $leads_converted = $this->lead->where('user_id',auth()->user()->id)->where('tag',3)->count();
            $leads_unconverted = $this->lead->where('user_id',auth()->user()->id)->where('tag',4)->count();
            $procedente = $this->lead->where('user_id',auth()->user()->id)->where('tag','3')->where('situation','3')->get()->count();
            $improcedente = $this->lead->where('user_id',auth()->user()->id)->where('tag','3')->where('situation','4')->get()->count();
            $resources = $this->lead->where('user_id',auth()->user()->id)->where('tag','3')->where('situation','5')->get()->count();
            
            $installments = $this->installment->where('user_id',auth()->user()->id)->get();
            $administratives = $this->administrative->where('reminder',1)->where('user_id',auth()->user()->id)->get();
            $events = $this->event->limit(1)->inRandomOrder()->get();
            $tickets = $this->ticket->where('user_id',auth()->user()->id)->whereIn('status',[1,2])->get();
            $terms = $this->term->where('user_id',auth()->user()->id)->get();
            $reminders = $this->lead->where('user_id',auth()->user()->id)->where('confirmed',1)->get();
            $users = $this->user->where('type','F')->get();

            $leads = $this->lead->where('user_id',auth()->user()->id)->where('tag',1)->orderBy('id','DESC')->get();
        }

        return view('home',[
            'leads_waiting' => $leads_waiting,
            'leads_converted' => $leads_converted,
            'leads_unconverted' => $leads_unconverted,
            'procedente' => $procedente,
            'improcedente' => $improcedente,
            'resources' => $resources,
            'installments' => $installments,
            'administratives' => $administratives,
            'events' => $events,
            'tickets' => $tickets,
            'terms' => $terms,
            'reminders' => $reminders,
            'users' => $users,
            'leads' => $leads
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
        $data = $request->all();

        Validator::make($data, [
            'name' => 'required|string|min:3',
            'phone' => 'required|string',
            'user_id' => 'required',
        ])->validate();
        
        $data['tag'] = 1;
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

            return redirect('home')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/leads')->with('error', 'Erro ao inserir o registro!');
        }
    }
}
