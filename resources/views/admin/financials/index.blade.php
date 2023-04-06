@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-start">
        <div>
            @can('search-financial')
                <form method="GET" action="{{ route('admin.financials.index') }}">
                    <div class="input-group input-group-md">
                        <select name="user_id" class="form-control mr-1">
                            <option value=""></option>
                            @foreach ($franchisees as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
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
    </div>
@stop

@section('content')

    <!-- -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $confirmation }}</h3>
                    <p>Confirmados pela matriz</p>
                </div>
                <div class="icon">
                    <i class="fa fa-flag"></i>
                </div>
                <a href="{{ route('admin.financials.findos') }}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $not_confirmation }}</h3>
                    <p>Aguardando confirmação</p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock"></i>
                </div>
                @if (false)
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
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>R$ {{ number_format($invoicing, 2, ',', '.') }}</h3>
                    <p>Faturamento</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-up"></i>
                </div>
                @if (false)
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
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>R$ {{ number_format($pending, 2, ',', '.') }}</h3>
                    <p>Pendentes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-down"></i>
                </div>
                @if (false)
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
            <h3 class="card-title">Registros do Financeiro</h3>
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
                        <th>Valor causa</th>
                        <th>Pagamento</th>
                        <th class="text-center">Comprovante(s)</th>
                        <th class="text-center">Parcelas</th>
                        @can('edit-financial')
                            <th class="text-center" style="width: 70px;">Edit</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($financials as $financial)
                        <tr>
                            <td class="py-2">
                                @if (isset($financial->user->image))
                                    <img src="{{ asset('storage/' . $financial->user->image) }}" alt="Photo"
                                        style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                @else
                                    <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo"
                                        class="img-circle img-size-32 mr-2">
                                @endif
                            </td>
                            <td class="py-2">
                                <p class="m-0" style="line-height: 1">
                                    {{ $financial->user->name }}<br />
                                    <small>
                                        @isset($financial->user->phone)
                                            {{ $financial->user->phone }} -
                                        @endisset
                                        {{ $financial->user->email }}
                                    </small>
                                </p>
                            </td>
                            <td class="py-2">
                                <p class="m-0" style="line-height: 1">
                                    {{ $financial->lead->name }}
                                    <br /><small>{{ $financial->lead->phone }} - {{ $financial->lead->address }},
                                        {{ $financial->lead->number }},
                                        {{ $financial->lead->district }}, {{ $financial->lead->city }},
                                        {{ $financial->lead->state }}</small>
                                </p>
                            </td>
                            <td class="py-2">
                                @if (isset($financial->value_causa))
                                    R$ {{ number_format($financial->value_causa, 2, ',', '.') }}
                                @else
                                    <span>Valor não informado</span>
                                @endif
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($financial->receipt_date)->format('d/m/Y') }}
                            </td>
                            <td class="py-2 text-center">
                                {{ count($financial->photos) }}
                            </td>
                            <td class="py-2 text-center">
                                {{ count($financial->installments) }}
                            </td>
                            @can('edit-financial')
                                <td class="py-2 px-2">
                                    <a href="{{ route('admin.financials.edit', ['id' => $financial->id]) }}"
                                        class="btn btn-block btn-info btn-sm border" title="Alterar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
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
            @if ($financials)
                <div class="px-2 pt-3">
                    {{ $financials->links('pagination::bootstrap-5') }}
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
