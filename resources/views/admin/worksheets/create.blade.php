@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.worksheets.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div id="message" class="alert alert-success mb-2" role="alert" style="max-width:900px; margin: auto">
                {{ session('success') }}
            </div>
        @elseif (session('alert'))
            <div id="message" class="alert alert-warning mb-2" role="alert" style="max-width:900px; margin: auto">
                {{ session('alert') }}
            </div>
        @elseif (session('error'))
            <div id="message" class="alert alert-danger mb-2" role="alert" style="max-width:900px; margin: auto">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.worksheets.store') }}" enctype="multipart/form-data"
            onsubmit="return mySubmit()">
            @csrf
            <div class="card card-info" style="max-width: 900px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de cadastro planilha:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <small>Título:</small>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror" maxlength="100" />
                                @error('title')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <small>Anexo de planilha formato: .pdf, .doc, .docx, .xlsx, .xlsm, .xlsb, .xltx</small><br />
                                <input type="file" name="file" class="@error('file') is-invalid @enderror" />
                                @error('file')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.worksheets.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" class="btn btn-md btn-info float-right" style="display: block;">
                        <i class="fas fa-save mr-1"></i>
                        Salvar dados
                    </button>
                    <a id="spinner" class="btn btn-md btn-info float-right text-center" style="display: none;">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Enviando...
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
