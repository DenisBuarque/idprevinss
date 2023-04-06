@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.tickets.update', ['id' => $ticket->id]) }}" enctype="multipart/form-data" onsubmit="return mySubmit()">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 900px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário edição de ticket:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>Informe a situação do seu ticket de atendimento:</small>
                                <select name="status" class="form-control">
                                    <option value="1" @if ($ticket->status == 1) selected @endif>Aberto</option>
                                    <option value="2" @if ($ticket->status == 2) selected @endif>Resolvido</option>
                                    <option value="3" @if ($ticket->status == 3) selected @endif>Pendente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <small>Comentários:</small>
                            <div class="form-group m-0">
                                <textarea name="description" placeholder="Digite aqui seu comentário."
                                    class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.tickets.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" class="btn btn-md btn-info float-right" style="display: block;">
                        <i class="fas fa-save mr-1"></i>
                        Salvar dados
                    </button>
                    <a id="spinner" class="btn btn-md btn-info float-right text-center" style="display: none;">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Atualizando...
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
