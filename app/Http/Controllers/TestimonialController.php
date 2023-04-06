<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    private $testimonial;

    public function __construct (Testimonial $testimonial)
    {
        $this->middleware('auth');
        $this->testimonial = $testimonial;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->search))
        {
            $search = $request->search;
            $query = $this->testimonial->query();

            $columns = ['title'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $testimonials = $query->orderBy('id','DESC')->get();

        } else {
            $testimonials = $this->testimonial->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.testimonials.index',['testimonials' => $testimonials]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.testimonials.create');
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
            'description' => 'required',
            'image' => 'sometimes|required|max:50000|mimes:jpg,jpeg,gif,png',
        ])->validate();

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $file = $request->image->store('testimonials','public');
            $data['image'] = $file;
        }

        $testimonial = $this->testimonial->create($data);
        if($testimonial)
        {
            return redirect('admin/testimonials')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/testimonial/create')->with('error', 'Erro ao inserir o registro!');
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
        $testimonial = $this->testimonial->find($id);
        if($testimonial){
            return view('admin.testimonials.edit',['testimonial' => $testimonial]);
        } else {
            return redirect('admin/testimonials')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->testimonial->find($id);

        Validator::make($data, [
            'name' => 'required|string|min:3|max:100',
            'description' => 'required',
            'image' => 'sometimes|required|max:50000|mimes:jpg,jpeg,gif,png',
        ])->validate();

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            
            if(($record['image'] != null) && (Storage::disk('public')->exists($record['image']))){
                Storage::disk('public')->delete($record['image']);
            } 

            $new_file = $request->image->store('testimonials','public');
            $data['image'] = $new_file;
        }

        if($record->update($data))
        {
            return redirect('admin/testimonials')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/testimonials')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->testimonial->find($id);
        if($data->delete())
        {
            if(Storage::disk('public')->exists($data['image'])){
                Storage::disk('public')->delete($data['image']);
            }

            return redirect('admin/testimonials')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/testimonials')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
