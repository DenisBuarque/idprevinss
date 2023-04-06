@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            @can('search-franchisee')
                <form method="GET" action="{{ route('admin.franchisees.index') }}">
                    <div class="input-group input-group-md">
                        <input type="text" name="search" class="form-control" placeholder="Nome">
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-info btn-flat">
                                <i class="fa fa-search mr-1"></i> Buscar
                            </button>
                        </span>
                    </div>
                </form>
            @endcan
        </div>
        @can('create-franchisee')
            <a href="{{ route('admin.franchisees.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
                <i class="fa fa-plus mr-1"></i> Adicionar novo
            </a>
        @endcan
    </div>
@stop

@section('content')

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
            <h3 class="card-title">Lista de franqueados</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2" style="width: 60px;">Photo</th>
                        <th class="py-2">Nome</th>
                        <th class="py-2">Contatos</th>
                        <th class="py-2">Advogado(s)</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        @can('edit-franchisee')
                            <th class="py-2 px-2 text-center" style="width: 70px;">Edit</th>
                        @endcan
                        @can('delete-franchisee')
                            <th class="py-2 px-2 text-center" style="width: 70px;">Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="py-2">
                                @if (isset($user->image))
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="Photo"
                                        style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                @else
                                    <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo"
                                        class="img-circle img-size-32 mr-2">
                                @endif
                            </td>
                            <td class="py-2">
                                <p class="m-0" style="line-height: 1">
                                    {{ $user->name }}<br />
                                    @isset($user->name)
                                        <small>{{ $user->address }}, {{ $user->number }}, {{ $user->district }},
                                            {{ $user->zip_code }}, {{ $user->city }}, {{ $user->state }}</small>
                                    @endisset
                                </p>
                            </td>
                            <td class="py-2">
                                <p class="m-0" style="line-height: 1">
                                    {{ $user->phone }}<br />
                                    <small>{{ $user->email }}</small>
                                </p>
                            </td>
                            <td class="py-2">
                                <div class="row">
                                    @foreach ($user->lawyers as $lawyer)
                                        <div class="col-md-3">
                                            @if (isset($lawyer->image))
                                                <img alt="Avatar" class="img-circle img-size-32"
                                                    src="{{ asset('storage/' . $lawyer->image) }}"
                                                    style="width: 32px; height: 32px;" title="{{ $lawyer->name }}">
                                            @else
                                                <img alt="Avatar" class="img-circle img-size-32"
                                                    src="https://dummyimage.com/28x28/b6b7ba/fff"
                                                    title="{{ $lawyer->name }}">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:m:s') }}</td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:m:s') }}</td>
                            @can('edit-franchisee')
                                <td class="py-2 px-2">
                                    <a href="{{ route('admin.franchisees.edit', ['id' => $user->id]) }}"
                                        class="btn btn-block btn-info btn-sm" title="Alterar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('delete-franchisee')
                                <td class="py-2 px-2">
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.franchisees.destroy', ['id' => $user->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block btn-sm" title="Excluir registro">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-center" colspan="7">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if (!$search && $users)
                <div class="px-2 pt-3">
                    {{ $users->links('pagination::bootstrap-5') }}
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
