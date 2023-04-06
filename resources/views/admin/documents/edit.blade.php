@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.documents.update', ['id' => $document->id]) }}" enctype="multipart/form-data" onsubmit="return mySubmit()">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 800px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário edição modelo de documento:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-0">
                                <small>Ação:</small>
                                <select name="action_id" class="form-control" required>
                                    <option value=""></option>
                                    @foreach ($actions as $action)
                                        @if ($action->id == $document->action_id)
                                            <option value="{{ $action->id }}" @selected(true)>{{ $action->name }}</option>
                                        @else
                                            <option value="{{ $action->id }}">{{ $action->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('action_id')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <small>Título:</small>
                                <input type="text" name="title" value="{{ $document->title ?? old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror" maxlength="100" />
                                @error('title')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <small>Anexo modelo de documento: .pdf, .doc, .docx</small><br />
                                <input type="file" name="file" class="@error('file') is-invalid @enderror" />
                                @error('file')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.documents.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
