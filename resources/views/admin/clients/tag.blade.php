@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            @can('search-client')
                <form method="GET" action="{{ route('admin.clients.index') }}">
                    <div class="input-group input-group-md">
                        @can('list-user')
                            <select name="franchisee" class="form-control mr-1">
                                <option value="">Franqueado</option>
                                @foreach ($franchisees as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        @endcan
                        <select name="situation" class="form-control mr-1">
                            <option value="">Ação</option>
                            <option value="1">Andamento em ordem</option>
                            <option value="2">Aguardando cumprimento</option>
                            <option value="3">Finalizado Procedente</option>
                            <option value="4">Finalizado Improcedente</option>
                            <option value="5">Recursos</option>
                        </select>
                        <input type="text" name="search" class="form-control" placeholder="Cliente">
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-info btn-flat">
                                <i class="fa fa-search mr-1"></i> Buscar
                            </button>
                        </span>
                    </div>
                </form>
            @endcan
        </div>
        @can('create-client')
            <a href="{{ route('admin.clients.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
                <i class="fa fa-plus mr-1"></i> Adicionar novo
            </a>
        @endcan
    </div>
@stop

@section('content')

    <!-- -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $converted_lead }}</h3>
                    <p>Clientes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('admin.clients.index') }}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $waiting }}</h3>
                    <p>Leads aguardando</p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock"></i>
                </div>
                @if ($waiting > 0)
                    <a href="{{ route('admin.leads.tag', ['tag' => 2]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endif
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $converted_lead }}</h3>
                    <p>Clientes convertidos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-up"></i>
                </div>
                @if ($converted_lead > 0)
                    <a href="{{ route('admin.clients.tag', ['tag' => 3]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endif
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $unconverted_lead }}</h3>
                    <p>Clientes não convertidos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-down"></i>
                </div>
                @if ($unconverted_lead > 0)
                    <a href="{{ route('admin.clients.tag', ['tag' => 4]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-none">
                <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes procedentes</span>
                    <span class="info-box-number">{{ $procedente }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg">
                <span class="info-box-icon bg-danger"><i class="fa fa-thumbs-down"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes improcedentes</span>
                    <span class="info-box-number">{{ $improcedente }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
                <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Obteve recursos</span>
                    <span class="info-box-number">{{ $resources }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fa fa-thumbs-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Auto findos</span>
                    <span class="info-box-number">0</span>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div id="message" class="alert alert-success mb-2" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div id="message" class="alert alert-warning mb-2" role="alert">
            {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div id="message" class="alert alert-danger mb-2" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- -->
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Lista de clientes convertidos</h3>
                            <div class="card-tools">

                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-striped  table-head-fixed">
                                <thead>
                                    <tr>
                                        <th class="py-2">Franqueado</th>
                                        <th class="py-2">Advogados</th>
                                        <th class="py-2">Cliente</th>
                                        <th class="py-2">Situação</th>
                                        @can('terms-client')
                                            <th class="py-2 px-2" style="width: 70px;">Prazos</th>
                                        @endcan
                                        @can('comments-client')
                                            <th class="py-2 px-2" style="width: 70px;">Comts</th>
                                        @endcan
                                        @can('edit-client')
                                            <th class="py-2 px-2" style="width: 70px;">Edit</th>
                                        @endcan
                                        @can('delete-client')
                                            <th class="py-2 px-2" style="width: 70px;">Del</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($leads as $lead)
                                        <tr>
                                            <td>
                                                <p class="m-0" style="line-height: 1">
                                                    {{ $lead->user->name }}<br />
                                                    <small>
                                                        @isset($lead->user->phone)
                                                            {{ $lead->user->phone }} - 
                                                        @endisset
                                                        {{ $lead->user->email }}
                                                    </small>
                                                </p>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    @foreach ($lead->lawyers as $lawyer)    
                                                        <div class="col-md-3">
                                                            @if (isset($lawyer->image))
                                                                <img alt="Avatar" class="img-circle img-size-32" src="{{asset('storage/'.$lawyer->image)}}" style="width: 32px; height: 32px;" title="{{$lawyer->name}}">
                                                            @else
                                                                <img alt="Avatar" class="img-circle img-size-32" src="https://dummyimage.com/28x28/b6b7ba/fff" title="{{$lawyer->name}}">
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <p class="m-0" style="line-height: 1">
                                                    {{ $lead->name }}<br/>
                                                    <small>
                                                        {{ $lead->phone }} - 
                                                        @isset($lead->address)
                                                            {{ $lead->address }}, {{ $lead->number }},
                                                                {{ $lead->district }}, {{ $lead->city }},
                                                                {{ $lead->state }}
                                                        @endisset
                                                    </small>
                                                </p>
                                            </td>
                                            <td>
                                                @php
                                                    $array_situations = [1 => 'Andamento em ordem', 2 => 'Aguardando cumprimento', 3 => 'Finalizado procedente', 4 => 'Finalizado improcedente', 5 => 'Recursos'];
                                                    foreach ($array_situations as $key => $value) {
                                                        if ($key == $lead->situation) {
                                                            echo '<span>' . $value . '</span>';
                                                        }
                                                    }
                                                @endphp
                                            </td>
                                            @can('terms-client')
                                                <td class='px-1'>
                                                    <a href="#" class="btn btn-sm border btn-block"
                                                        title="Prazos a cumprir" data-toggle="modal"
                                                        data-target="#modal-xl{{ $lead->id }}">
                                                        <i class="fa fa-clock"></i> {{ count($lead->terms) }}
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('comments-client')
                                                <td class='px-1'>
                                                    <a href="{{ route('admin.clients.show', ['id' => $lead->id]) }}"
                                                        title="Comentários do cliente" class="btn btn-sm border btn-block"><i
                                                            class="fa fa-comments"></i>
                                                        {{ count($lead->feedbackLeads) }}
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('edit-client')
                                                <td class='px-1'>
                                                    <a href="{{ route('admin.clients.edit', ['id' => $lead->id]) }}"
                                                        title="Alterar registro" class="btn btn-info btn-sm btn-block">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('delete-client')
                                                <td class='px-1'>
                                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                                        action="{{ route('admin.clients.destroy', ['id' => $lead->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm btn-block"
                                                            title="Excluir registro">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Nenhum registro encontrado</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($leads)
                            <div class="card-footer">
                                {{ $leads->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @foreach ($leads as $lead)
                <div class="modal fade" id="modal-xl{{ $lead->id }}" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Prazos</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                                <table class="table table-hover table-striped  table-head-fixed">
                                    <thead>
                                        <tr>
                                            <th>Comentário</th>
                                            <th>Prazo</th>
                                            <th>Audiência</th>
                                            <th>Endereço</th>
                                            <th>Status</th>
                                            <th class="py-2 px-2" style="width: 70px;">Edit</th>
                                            <th class="py-2 px-2" style="width: 70px;">Del</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($lead->terms as $term)
                                            <tr>
                                                <td>{{ $term->comments }}</td>
                                                <td>{{ \Carbon\Carbon::parse($term->term)->format('d/m/Y') }}</td>
                                                <td>
                                                    @isset($term->audiencia)
                                                        {{ \Carbon\Carbon::parse($term->audiencia)->format('d/m/Y') }}
                                                    @endisset 
                                                    @isset($term->hour)
                                                        {{ $term->hour }}
                                                    @endisset
                                                </td>
                                                <td>{{ $term->address }}</td>
                                                <td>
                                                    @if ($term->tag == 1)
                                                        <span>Aguardando</span>
                                                    @else
                                                        <span>Cumprido</span>
                                                    @endif
                                                </td>
                                                <td class='px-1'>
                                                    <a href="{{ route('admin.terms.edit', ['id' => $term->id]) }}"
                                                        class="btn btn-info btn-xs btn-block">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td class='px-1'>
                                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                                        action="{{ route('admin.terms.delete', ['id' => $term->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-xs btn-block">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Nenhum registro encontrado</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <a href="{{ route('admin.terms.create', ['id' => $lead->id]) }}" class="btn btn-primary">
                                    <i class="fa fa-plus mr-1"></i> Adicionar Prazo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="col-md-3 col-12">

            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Parcelas Vencidas</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped table-head-fixed">
                        <thead>
                            <tr>
                                <th class="py-2">Data</th>
                                <th class="py-2">Franqueado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($installments as $value)
                                @if (\Carbon\Carbon::now()->format('Y-m-d') > $value->date && $value->active == 'S')
                                    <tr>
                                        <td class="py-2">
                                            <a
                                                href="{{ route('admin.financials.edit', ['id' => $value->financial_id]) }}">
                                                {{ \Carbon\Carbon::parse($value->date)->format('d/m/Y') }}
                                            </a>
                                        </td>
                                        <td class="py-2">{{ $value->financial->user->name }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Prazos não cumpridos</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped table-head-fixed">
                        <thead>
                            <tr>
                                <th class="py-2">Data</th>
                                <th class="py-2">Cliente</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($terms as $term)
                                @if (\Carbon\Carbon::now()->format('Y-m-d') > $term->term && $term->tag == 1)
                                    <tr>
                                        <td class="py-2">
                                            <a
                                                href="{{ route('admin.terms.edit', ['id' => $term->id]) }}">{{ \Carbon\Carbon::parse($term->term)->format('d/m/Y') }}</a>
                                        </td>
                                        <td class="py-2">{{ $term->lead->name }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Lembrete de clientes</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped table-head-fixed">
                        <thead>
                            <tr>
                                <th class="py-2">Cliente</th>
                                <th class="py-2">Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads as $value)
                                @if ($value->confirmed == 1)
                                    <tr>
                                        <td class="py-2">
                                            <a href="{{ route('admin.clients.edit', ['id' => $value->id]) }}">{{ $value->name }}</a>
                                        </td>
                                        <td class="py-2">
                                            @php
                                                $array_situations = [1 => 'Andamento em ordem', 2 => 'Aguardando cumprimento', 3 => 'Finalizado procedente', 4 => 'Finalizado improcedente', 5 => 'Recursos'];
                                                foreach ($array_situations as $key => $item) {
                                                    if ($key == $value->situation) {
                                                        echo '<span>' . $item . '</span>';
                                                    }
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function confirmaExcluir() {
            var conf = confirm("Deseja mesmo excluir? Os dados serão perdidos e não poderão ser recuperados.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }

        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);
    </script>
@stop
