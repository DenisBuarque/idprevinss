@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success mb-2" role="alert" style="max-width: 900px; margin: auto;">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger mb-2" role="alert" style="max-width: 900px; margin: auto;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.terms.store') }}" onsubmit="return mySubmit()">
            @csrf
            <input type="hidden" name="user_id" value="{{ $lead->user_id }}" />
            <input type="hidden" name="lead_id" value="{{ $lead->id }}" />
            <div class="card card-info" style="max-width: 900px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário cadastro de prazo:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Prazo: *</small>
                                <input type="date" name="term" value="{{ old('term') }}"
                                    class="form-control @error('term') is-invalid @enderror" />
                                @error('term')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Data Audiência:</small>
                                <input type="date" name="audiencia" value="{{ old('audiencia') }}"
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Horários:</small>
                                <input type="time" name="hour" value="{{ old('hour') }}" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Etiqueta:</small>
                                <select name="tag" class="form-control">
                                    <option value="1">Aguardando cumprimento</option>
                                    <option value="2">Cumprido</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-0">
                                <small>Endereço:</small>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control"
                                    maxlength="250" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-0">
                                <small>Comentários: *</small>
                                <textarea name="comments" class="form-control @error('comments') is-invalid @enderror" class="w-full h-28"></textarea>
                                @error('comments')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card card-info" style="max-width: 900px; margin: auto">

                <div class="card-footer">
                    <a href="{{ route('admin.clients.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" onClick="ocultarExibir()" class="btn btn-md btn-info float-right">
                        <i class="fas fa-save mr-2"></i>
                        Salvar dados
                    </button>
                    <a id="spinner" class="btn btn-md btn-info float-right text-center">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Enviado...
                    </a>
                </div>
            </div>

        </form>
        <br>
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
    </script>
@stop
