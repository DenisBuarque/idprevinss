@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <!-- -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ count($leads) }}</h3>
                    <p>Novos Leads</p>
                </div>
                <div class="icon">
                    <i class="fa fa-flag"></i>
                </div>
                <a href="/home" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $leads_waiting }}</h3>
                    <p>Leads aguardando</p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock"></i>
                </div>
                <a href="{{ route('admin.leads.tag', ['tag' => 2]) }}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $leads_converted }}</h3>
                    <p>Leads convertidos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-up"></i>
                </div>
                <a href="{{ route('admin.clients.tag', ['tag' => 3]) }}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $leads_unconverted }}</h3>
                    <p>Ledas não convertidos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-down"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-none">
                <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Finalizados Procedentes</span>
                    <span class="info-box-number">{{ $procedente }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg">
                <span class="info-box-icon bg-danger"><i class="fa fa-thumbs-down"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Finalizados Improcedentes</span>
                    <span class="info-box-number">{{ $improcedente }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
                <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Obteve Recursos</span>
                    <span class="info-box-number">{{ $resources }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fa fa-thumbs-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Auto Findos</span>
                    <span class="info-box-number">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- -->
    <div class="row">
        <div class="col-md-9 col-12">

            @if (session('success'))
                <div id="message" class="alert alert-success mb-2" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Lista de novos leads de atendimento</h3>
                            <div class="card-tools">
                                @can('create-lead')
                                    <a href="" class="btn btn-sm btn-info btn-block" data-toggle="modal"
                                        data-target="#modal-xl">
                                        <i class="fa fa-plus mr-1"></i> Adicionar lead
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0" style="height: 300px;">
                            <table class="table table-hover table-striped  table-head-fixed">
                                <thead>
                                    <tr>
                                        <th class="py-2">Franqueado</th>
                                        <th class="py-2">Nome</th>
                                        <th class="py-2 px-2" style="width: 50px;"></th>
                                        <th class="py-2 px-2" style="width: 70px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($leads as $lead)
                                        <tr>
                                            <td class="py-2">
                                                <p class="m-0" style="line-height: 1">
                                                    {{ $lead->user->name }}<br />
                                                    @isset($lead->user->phone)
                                                        <small>{{ $lead->user->phone }}</small>
                                                    @endisset
                                                </p>
                                            </td>
                                            <td class="py-2">
                                                <p class="m-0" style="line-height: 1">{{ $lead->name }}<br />
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
                                            <td class="py-2 px-0">
                                                <a href="{{ route('admin.leads.show', ['id' => $lead->id]) }}"
                                                    class="btn btn-block btn-success btn-sm" title="Comentários do lead">
                                                    <i class="fa fa-comments"></i> {{ count($lead->feedbackLeads) }}
                                                </a>
                                            </td>
                                            <td class="py-2 px-2">
                                                <a href="{{ route('admin.leads.edit', ['id' => $lead->id]) }}"
                                                    class="btn btn-block btn-info btn-sm" title="Alterar registro">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span>Nenhum lead adicionado.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-hourglass-end mr-1" aria-hidden="true"></i> Prazos não
                                cumpridos</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped table-head-fixed">
                                <thead>
                                    <tr>
                                        <th class="py-2">Cliente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($terms as $term)
                                        @if (\Carbon\Carbon::now()->format('Y-m-d') > $term->term && $term->tag == 1)
                                            <tr>
                                                <td class="py-2">
                                                    <p class="m-0" style="line-height: 1">
                                                        <small>{{ \Carbon\Carbon::parse($term->term)->format('d/m/Y') }}</small><br />
                                                        <a href="{{ route('admin.terms.edit', ['id' => $term->id]) }}">
                                                            {{ $term->lead->name }}
                                                        </a>
                                                    </p>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-bell" aria-hidden="true"></i> Lembretes</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped table-head-fixed">
                                <thead>
                                    <tr>
                                        <th class="py-2">Cliente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reminders as $reminder)
                                        <tr>
                                            <td class="py-2">

                                                <p class="m-0" style="line-height: 1">
                                                    <a href="{{ route('admin.clients.edit', ['id' => $reminder->id]) }}">
                                                        {{ $reminder->name }}
                                                    </a><br />
                                                    <small>
                                                        @if ($reminder->tag == 1)
                                                            Aguardando
                                                        @elseif($reminder->tag == 2)
                                                            Convertdo
                                                        @else
                                                            Não convertodo
                                                        @endif
                                                    </small>
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-tag mr-1"></i> Tickets abertos</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped table-head-fixed">
                                <thead>
                                    <tr>
                                        <th class="py-2">Franqueado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td class="py-2">
                                                <p class="m-0" style="line-height: 1">
                                                    <small>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:m:s') }}</small><br />
                                                    <a href="{{ route('admin.tickets.index') }}">
                                                        {{ $ticket->user->name }}
                                                    </a>
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card card-danger" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                        <div class="card-header">
                            <h3 class="card-title">Pagamentos vencidos</h3>
                            <div class="card-tools">

                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-striped table-head-fixed">
                                <thead>
                                    <tr>
                                        <th class="py-2">Data</th>
                                        <th class="py-2">Franqueado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($installments as $value)
                                        @if (\Carbon\Carbon::now()->format('Y-m-d') > $value->date && $value->active == 'N')
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
                </div>
                <div class="col-md-4 col-12">
                    <div class="card card-success"
                        style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                        <div class="card-header">
                            <h3 class="card-title">Data Cessações:</h3>
                            <div class="card-tools">

                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-striped table-head-fixed">
                                <thead>
                                    <tr>
                                        <th class="py-2">Cliente</th>
                                        <th class="py-2">Cessação</th>
                                        <th class="py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($administratives as $value)
                                        <tr>
                                            <td class="py-2">{{ $value->name }}</td>
                                            <td class="py-2">
                                                <a href="{{ route('admin.administratives.edit', ['id' => $value->id]) }}">
                                                    {{ \Carbon\Carbon::parse($value->concessao_date)->format('d/m/Y') }}
                                                </a>
                                            </td>
                                            <td class="py-2">
                                                @php
                                                    $end = \Carbon\Carbon::parse($value->concessao_date);
                                                    $now = \Carbon\Carbon::now();
                                                    $diff = $end->diffInDays($now);
                                                    echo $diff . ' dias.';
                                                @endphp
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    @can('confirm-payment')
                        <div class="card card-success"
                            style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                            <div class="card-header">
                                <h3 class="card-title">Pagamentos Realizados</h3>
                                <div class="card-tools">

                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover table-striped table-head-fixed">
                                    <thead>
                                        <tr>
                                            <th class="py-2">Data</th>
                                            <th class="py-2">Franqueado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($installments as $value)
                                            @if ($value->active == 'S')
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
                    @endcan
                </div>
            </div>

        </div>

        <div class="col-md-3 col-12">






            <div class="timeline">
                <div class="time-label">
                    <span class="bg-green">Confira os Eventos</span>
                </div>
                @foreach ($events as $event)
                    @if (\Carbon\Carbon::now()->format('Y-m-d') < $event->date)
                        <div>
                            <i class="fas fa-bell bg-blue"></i>
                            <div class="timeline-item">
                                @isset($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="photo" style="width: 100%" />
                                @endisset
                                <span class="time"><i class="fas fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</span>
                                <h3 class="timeline-header text-bold">{{ $event->title }}</h3>
                                <div class="timeline-body">
                                    {{ $event->description }}
                                </div>
                                <div class="timeline-footer">
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div>
                    <i class="fas fa-clock bg-gray"></i>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modal-xl" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Lead de atendimento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('home.store') }}" onsubmit="return mySubmit()">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group m-0">
                                    <small>Nome: *</small>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                                    @error('name')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group m-0">
                                    <small>Telefones: *</small>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        class="form-control @error('phone') is-invalid @enderror" maxlength="50" />
                                    @error('phone')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group m-0">
                                    <small>Franqueado: *</small>
                                    <select name="user_id" class="form-control @error('phone') is-invalid @enderror">
                                        <option value="">Selecione um franqueado</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group m-0">
                                    <small>Cep:</small>
                                    <input type="text" name="zip_code" id="cep" value="{{ old('zip_code') }}"
                                        class="form-control @error('zip_code') is-invalid @enderror" maxlength="9"
                                        onkeypress="mascara(this, '#####-###')" onblur="pesquisacep(this.value);" />
                                    @error('zip_code')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group m-0">
                                    <small>Endereço:</small>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                                        class="form-control @error('address') is-invalid @enderror" maxlength="250" />
                                    @error('address')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group m-0">
                                    <small>Número:</small>
                                    <input type="text" name="number" value="{{ old('number') }}"
                                        class="form-control @error('number') is-invalid @enderror" placeholder="nº"
                                        maxlength="5" />
                                    @error('number')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-0">
                                    <small>Bairro:</small>
                                    <input type="text" name="district" id="district" value="{{ old('district') }}"
                                        class="form-control @error('district') is-invalid @enderror" maxlength="50" />
                                    @error('district')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-0">
                                    <small>Cidade:</small>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                                        class="form-control @error('city') is-invalid @enderror" maxlength="50" />
                                    @error('city')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group m-0">
                                    <small>Estado:</small>
                                    <input type="text" name="state" id="state" value="{{ old('state') }}"
                                        class="form-control @error('state') is-invalid @enderror" maxlength="2" />
                                    @error('state')
                                        <div class="text-red">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group m-0">
                                    <small>Comentários:</small>
                                    <textarea name="comments" class="form-control" placeholder="Digite aqui o seu comentário.">{{ old('comments') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button id=button type="submit" class="btn btn-info">
                            <i class="fa fa-save mr-1"></i> Salvar registro
                        </button>
                        <a id="spinner" class="btn btn-md btn-info float-right text-center" style="display: none;">
                            <div id="spinner" class="spinner-border" role="status"
                                style="width: 20px; height: 20px;">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Enviando...
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        document.getElementById("button").style.display = "block";
        document.getElementById("spinner").style.display = "none";

        function mySubmit() {
            document.getElementById("button").style.display = "none";
            document.getElementById("spinner").style.display = "block";
        }

        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);

        //criação de mascara
        function mascara(t, mask) {
            var i = t.value.length;
            var saida = mask.substring(1, 0);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                t.value += texto.substring(0, 1);
            }
        }

        // Busca pelo cep
        function limpa_formulario_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('address').value = ("");
            document.getElementById('district').value = ("");
            document.getElementById('city').value = ("");
            document.getElementById('state').value = ("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('address').value = (conteudo.logradouro);
                document.getElementById('district').value = (conteudo.bairro);
                document.getElementById('city').value = (conteudo.localidade);
                document.getElementById('state').value = (conteudo.uf);
            } else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }

        function pesquisacep(valor) {
            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');
            //Verifica se campo cep possui valor informado.
            if (cep != "") {
                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;
                //Valida o formato do CEP.
                if (validacep.test(cep)) {
                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('address').value = "...";
                    document.getElementById('district').value = "...";
                    document.getElementById('city').value = "...";
                    document.getElementById('state').value = "...";
                    //Cria um elemento javascript.
                    var script = document.createElement('script');
                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);

                } else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        }
    </script>
@stop
