@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            @can('search-lead')
                <form method="GET" action="{{ route('admin.leads.index') }}">
                    <div class="input-group input-group-md">
                        @can('list-franchisee')
                            <select name="franchisee" class="form-control mr-1">
                                <option value=""></option>
                                @foreach ($franchisees as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        @endcan
                        <input type="text" name="search" class="form-control">
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-info btn-flat">
                                <i class="fa fa-search mr-1"></i> Buscar
                            </button>
                        </span>
                    </div>
                </form>
            @endcan
        </div>
        @can('create-lead')
            <a href="{{ route('admin.leads.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
                    <h3>{{ $leads_total }}</h3>
                    <p>Leads</p>
                </div>
                <div class="icon">
                    <i class="fa fa-flag"></i>
                </div>
                <a href="{{ route('admin.leads.index') }}" class="small-box-footer">
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
                    <p>Leads convertidos</p>
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
                    <p>Ledas não convertidos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-down"></i>
                </div>
                @if ($unconverted_lead > 0)
                    <a href="#" class="small-box-footer">
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


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de leads de atendimento</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px;">Photo</th>
                        <th>Franqueado</th>
                        <th>Cliente</th>
                        <th></th>
                        <th>Criado</th>
                        <th>Atualizado</th>
                        @can('comments-lead')
                            <th class="text-center" style="width: 70px;">Comts</th>
                        @endcan
                        @can('edit-lead')
                            <th class="text-center" style="width: 70px;">Edit</th>
                        @endcan
                        @can('delete-lead')
                            <th class="text-center" style="width: 70px;">Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td class="py-2">
                                @if (isset($lead->user->image))
                                    <img src="{{ asset('storage/' . $lead->user->image) }}" alt="Photo"
                                        style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                @else
                                    <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo"
                                        class="img-circle img-size-32 mr-2">
                                @endif
                            </td>
                            <td class="py-2">
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
                            <td class="py-2">
                                <p class="m-0" style="line-height: 1">{{ $lead->name }}
                                    @isset($lead->address)
                                        <br /><small>{{ $lead->phone }} / {{ $lead->address }}, nº {{ $lead->number }},
                                            {{ $lead->district }}, {{ $lead->city }}, {{ $lead->state }}</small>
                                    @endisset
                                </p>
                            </td>
                            <td class="py-2">
                                @php
                                    $array_tags = [1 => 'Novo Lead', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                    foreach ($array_tags as $key => $value) {
                                        if ($key == $lead->tag) {
                                            if ($key == 1) {
                                                echo '<small class="badge badge-info">' . $value . '</small>';
                                            } elseif ($key == 2) {
                                                echo '<small class="badge badge-warning">' . $value . '</small>';
                                            } elseif ($key == 3) {
                                                echo '<small class="badge badge-success">' . $value . '</small>';
                                            } else {
                                                echo '<small class="badge badge-danger">' . $value . '</small>';
                                            }
                                        }
                                    }
                                @endphp
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($lead->created_at)->format('d/m/Y H:m:s') }}</td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($lead->updated_at)->format('d/m/Y H:m:s') }}</td>
                            @can('comments-lead')
                                <td class="px-1 py-2">
                                    <a href="{{ route('admin.leads.show', ['id' => $lead->id]) }}"
                                        class="btn btn-sm btn-light btn-block">
                                        <i class="fa fa-comments"></i> {{ count($lead->feedbackLeads) }}
                                    </a>
                                </td>
                            @endcan
                            @can('edit-lead')
                                <td class="py-2 px-2">
                                    <a href="{{ route('admin.leads.edit', ['id' => $lead->id]) }}"
                                        class="btn btn-block btn-info btn-sm border" title="Alterar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('delete-lead')
                                <td class="py-2 px-2">
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.leads.destroy', ['id' => $lead->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block btn-sm"
                                            title="Excluir registro">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-center" colspan="9">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($leads)
                <div class="px-2 pt-3">
                    {{ $leads->links('pagination::bootstrap-5') }}
                </div>
            @endif
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
