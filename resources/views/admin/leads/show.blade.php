@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3 class="m-0">Detalhes do lead</h3>
        </div>
        <a href="{{ route('admin.leads.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registros
        </a>
    </div>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if (isset($lead->user->image))
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="{{ asset('storage/' . $lead->user->image) }}" alt="Photo"
                                        style="width: 150px; height: 150px;">
                                @else
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="https://dummyimage.com/150x150/b6b7ba/fff" alt="Photo"
                                        style="width: 150px; height: 150px;">
                                @endif
                            </div>
                            <h3 class="profile-username text-center">{{ $lead->user->name }}</h3>

                            @if ($lead->user->type == 'A')
                                <p class="text-muted text-center">Administrador</p>
                            @else
                                <p class="text-muted text-center">Franqueado</p>
                            @endif

                            @can('edit-lead')
                            <a href="{{route('admin.leads.edit',['id' => $lead->id])}}" class="btn btn-default block">
                                <i class="fa fa-edit"></i> Editar lead
                            </a>
                            @endcan
                            <!-- ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Comentários</b> <a class="float-right">0</a>
                                        </li>
                                    </ul -->
                        </div>
                    </div>


                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Advogado(s) do Franqueado</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body p-0">

                            <ul class="users-list clearfix">
                                @forelse ($lead->user->lawyers as $lawyer)
                                    <li>
                                        @if (isset($lawyer->image))
                                            <img src="{{ asset('storage/' . $lawyer->image) }}"
                                                class="profile-user-img img-fluid img-circle"
                                                style="width: 60px; height: 60px;" alt="User Image">
                                        @else
                                            <img src="https://dummyimage.com/100x100/b6b7ba/fff"
                                                class="profile-user-img img-fluid img-circle" alt="User Image">
                                        @endif

                                        <a class="users-list-name" href="#">{{ $lawyer->name }}</a>
                                        <span class="users-list-date">Adv.</span>
                                    </li>
                                @empty
                                    <span class='text-center d-block pt-2'>Nenhum advogado cadastrado.</span>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informações sobre o lead</h3>
                        </div>
                        <div class="card-body">
                            @isset($lead->address)
                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Endereço:</strong>
                                <p class="text-muted">{{ $lead->address }}, nº {{ $lead->number }}, {{ $lead->district }},
                                    {{ $lead->city }}, {{ $lead->state }}</p>
                                <hr />
                            @endisset
                            <strong><i class="far fa-calendar mr-1"></i> Criado</strong>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($lead->created_at)->format('d/m/Y H:m:s') }}</p>
                            <hr />
                            <strong><i class="fa fa-edit mr-1"></i> Atualizado</strong>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($lead->updated_at)->format('d/m/Y H:m:s') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-white">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Atualizado</span>
                                    <span
                                        class="info-box-number text-center text-muted mb-0">{{ $lead->updated_at->format('d/m/Y H:m:s') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-white">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Etiqueta</span>
                                    @php
                                        $array_tags = [1 => 'Novo Lead', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                        foreach ($array_tags as $key => $value) {
                                            if ($key == $lead->tag) {
                                                echo '<span class="info-box-number text-center text-muted mb-0">' . $value . '</span>';
                                            }
                                        }
                                    @endphp
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-white">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Situação</span>
                                    @php
                                        $arr = [1 => 'Andamento em ordem', 2 => 'Aguardando cumprimento', 3 => 'Finalizado Procedente', 4 => 'Finalizado Improcedente', 5 => 'Recursos'];
                                        foreach ($arr as $key => $value) {
                                            if ($key == $lead->situation) {
                                                echo '<span class="info-box-number text-center text-muted mb-0">' . $value . '</span>';
                                            }
                                        }
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div id="message" class="alert alert-success mb-2" role="alert">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div id="message" class="alert alert-danger mb-2" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card direct-chat direct-chat-primary">
                        <div class="card-header ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title">Comentários do lead</h3>
                            <div class="card-tools">
                                <span title="3 New Messages" class="badge badge-primary">{{ count($feedbackLeads) }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="direct-chat-messages">
                                @foreach ($feedbackLeads as $feed)
                                    @if ($feed->user_id == auth()->user()->id)
                                        <div class="direct-chat-msg">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-left">{{ auth()->user()->name }}</span>
                                                <span
                                                    class="direct-chat-timestamp float-right">{{ $feed->created_at->format('d/m/Y H:m:s') }}</span>
                                            </div>
                                            @if (isset($feed->user->image))
                                                <img class="direct-chat-img"
                                                    src="{{ asset('storage/' . $feed->user->image) }}" alt="Foto">
                                            @else
                                                <img class="direct-chat-img" src="https://dummyimage.com/28x28/b6b7ba/fff"
                                                    alt="Foto">
                                            @endif
                                            <div class="direct-chat-text">
                                                {{ $feed->comments }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="direct-chat-msg right">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-right">{{ $feed->user->name }}</span>
                                                <span
                                                    class="direct-chat-timestamp float-left">{{ $feed->created_at->format('d/m/Y H:m:s') }}</span>
                                            </div>
                                            @if (isset($feed->user->image))
                                                <img class="direct-chat-img"
                                                    src="{{ asset('storage/' . $feed->user->image) }}" alt="Foto">
                                            @else
                                                <img class="direct-chat-img" src="https://dummyimage.com/28x28/b6b7ba/fff"
                                                    alt="Foto">
                                            @endif
                                            <div class="direct-chat-text">
                                                {{ $feed->comments }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer">
                            <form method="POST" action="{{ route('admin.leads.feedback') }}">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}" />
                                <div class="input-group">
                                    <input type="text" name="comments" required placeholder="Digite seu comentário aqui."
                                        class="form-control">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);
    </script>
@stop
