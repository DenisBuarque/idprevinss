@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            @can('search-ticket')
                <form method="GET" action="{{ route('admin.tickets.index') }}">
                    <div class="input-group input-group-md">
                        <select name="franchisee" class="form-control">
                            <option value="">Franqueado</option>
                            @foreach ($franchisees as $franchisee)
                                <option value="{{ $franchisee->id }}">{{ $franchisee->name }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-info btn-flat">
                                <i class="fa fa-search mr-1"></i> Buscar
                            </button>
                        </span>
                    </div>
                </form>
            @endcan
        </div>
        @can('create-ticket')
            <a href="{{ route('admin.tickets.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
                <i class="fa fa-plus mr-1"></i> Abrir Ticket de atendimento
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

    <div class="row">
        <div class="col-md-9 col-12">
            @forelse ($tickets as $ticket)
                @if ($ticket->status == 1 or $ticket->status == 3)
                    <div class="card card-widget">
                    @else
                        <div class="card card-widget collapsed-card">
                @endif

                <div class="card-header">
                    <div class="user-block">
                        @if (isset($ticket->user->image))
                            <img class="img-circle" src="{{ asset('storage/' . $ticket->user->image) }}" alt="Photo">
                        @else
                            <img class="img-circle" src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo">
                        @endif
                        <span class="username">
                            <a href="#">{{ $ticket->user->name }}</a>
                            @php
                                if ($ticket->status == 1) {
                                    echo '<small class="badge badge-warning ml-2">Ticket Aberto</small>';
                                } elseif ($ticket->status == 2) {
                                    echo '<small class="badge badge-success ml-2">Ticket Resolvido</small>';
                                } else {
                                    echo '<small class="badge badge-danger ml-2">Ticket Pendente</small>';
                                }
                            @endphp
                        </span>
                        <span class="description">
                            {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:m:s') }} -
                            {{ \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:m:s') }}
                        </span>
                    </div>

                    <div class="card-tools">
                        @if ($ticket->status == 1 or $ticket->status == 3)
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                            </button>
                        @endif
                    </div>

                </div>

                <div class="card-body">
                    <p>{{ $ticket->comments }}</p>

                    <form method="POST" onsubmit="return(confirmaExcluir())"
                        action="{{ route('admin.tickets.destroy', ['id' => $ticket->id]) }}">
                        @csrf
                        @method('DELETE')
                        @can('edit-ticket')
                            <a href="{{ route('admin.tickets.edit', ['id' => $ticket->id]) }}" class="btn btn-default btn-sm">
                                <i class="fa fa-edit"></i> Editar
                            </a>
                        @endcan
                        @can('delete-ticket')
                            <button type="submit" class="btn btn-default btn-sm" title="Excluir registro">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        @endcan

                        <span class="float-right text-muted">{{ count($ticket->feedbackTickets) }} comentários</span>
                    </form>

                </div>

                <div class="card-footer card-comments">
                    @foreach ($ticket->feedbackTickets as $value)
                        <div class="card-comment">
                            @if (isset($value->user->image))
                                <img class="img-circle img-sm" src="{{ asset('storage/' . $ticket->user->image) }}" alt="Photo">
                            @else
                                <img class="img-circle img-sm" src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo">
                            @endif
                            <div class="comment-text">
                                <span class="username">
                                    {{ $value->user->name }}
                                    <span
                                        class="text-muted float-right">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:m:s') }}</span>
                                </span>
                                {{ $value->description }}
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="card-footer">
                    @can('comments-ticket')
                        <form action="{{ route('admin.tickets.feedback', ['id' => $ticket->id]) }}" method="POST">
                            @csrf
                            @if (isset(auth()->user()->image))
                                <img class="img-fluid img-circle img-sm" src="{{ asset('storage/' . auth()->user()->image) }}"
                                    alt="Photo">
                            @else
                                <img class="img-fluid img-circle img-sm" src="https://dummyimage.com/28x28/b6b7ba/fff"
                                    alt="Photo">
                            @endif

                            <div class="img-push">
                                <div class="input-group">
                                    <input type="text" name="description" placeholder="Digite sua mensagem"
                                        class="form-control">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-envelope"></i>
                                            Enviar</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    @endcan
                </div>
        </div>
    @empty
        <div class="text-center p-3">
            <span>Nenhum ticket aberto ou pendente.</span>
        </div>
        @endforelse

    </div>

    <div class="col-md-3 col-12">
        <div class="row">
            <div class="col-md-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $open }}</h3>
                        <p>Tickets Abertos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-clock"></i>
                    </div>
                    <a href="{{ route('admin.tickets.category', ['id' => 1]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pending }}</h3>
                        <p>Tickets pendentes</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-thumbs-down"></i>
                    </div>
                    <a href="{{ route('admin.tickets.category', ['id' => 3]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ count($tickets) }}</h3>
                        <p>Total de Tickets</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-tag"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        &nbsp;
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $resolved }}</h3>
                        <p>Tickets Resolvidos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-thumbs-up"></i>
                    </div>
                    <a href="{{ route('admin.tickets.category', ['id' => 2]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
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
