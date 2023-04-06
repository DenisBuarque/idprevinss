<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Event;

class EventController extends Controller
{
    private $event;

    public function __construct(Event $event)
    {
        $this->middleware('auth');
        $this->event = $event;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->event->query();

        if($request->has('search')){
            $columns = ['title'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$request->search.'%');
            endforeach;
        }

        $events = $query->orderBy('id','DESC')->paginate(10);
        
        return view('admin.events.index',['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.events.create');
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
            'date' => 'date_format:"Y-m-d"|required',
            'description' => 'required',
            'image' => 'sometimes|required|max:50000|mimes:jpg,jpeg,gif,png',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $file = $request->image->store('events','public');
            $data['image'] = $file;
        }

        $event = $this->event->create($data);
        if($event)
        {
            return redirect('admin/events')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/event/create')->with('error', 'Erro ao inserir o registro!');
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
        $event = $this->event->find($id);
        if($event){
            return view('admin.events.edit',['event' => $event]);
        } else {
            return redirect('admin/events')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->event->find($id);

        Validator::make($data, [
            'title' => 'required|string|min:3|max:100',
            'date' => 'date_format:"Y-m-d"|required',
            'description' => 'required',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            if(Storage::disk('public')->exists($record['image'])){
                Storage::disk('public')->delete($record['image']);
            } 

            $new_file = $request->image->store('events','public');
            $data['image'] = $new_file;
        }

        if($record->update($data))
        {
            return redirect('admin/events')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/events')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->event->find($id);
        if($data->delete())
        {
            if(($data['file'] != null) && (Storage::disk('public')->exists($data['image']))){
                Storage::disk('public')->delete($data['image']);
            } 

            return redirect('admin/events')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/events')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
