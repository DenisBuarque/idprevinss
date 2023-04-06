@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            @can('search-testimonial')
                <form method="GET" action="{{ route('admin.testimonials.index') }}">
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
        @can('create-testimonial')
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
            <h3 class="card-title">Registros de testemunhas</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px;">Photo</th>
                        <th>Nome</th>
                        <th>Criado</th>
                        <th>Atualizado</th>
                        @can('edit-testimonial')
                            <th class="text-center" style="width: 70px;">Edit</th>
                        @endcan
                        @can('delete-testimonial')
                            <th class="text-center" style="width: 70px;">Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $testimonial)
                        <tr>
                            <td class="py-2">
                                @if (isset($testimonial->image))
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" alt="Photo"
                                        style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                @else
                                    <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo"
                                        class="img-circle img-size-32 mr-2">
                                @endif
                            </td>
                            <td class="py-2">
                                <p class="m-0" style="line-height: 1">
                                    {{ $testimonial->name }}<br />
                                    <small>{{ $testimonial->description }}</small>
                                </p>
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($testimonial->created_at)->format('d/m/Y') }}</td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($testimonial->updated_at)->format('d/m/Y') }}</td>
                            @can('edit-testimonial')
                                <td class="py-2 px-2">
                                    <a href="{{ route('admin.testimonials.edit', ['id' => $testimonial->id]) }}"
                                        class="btn btn-block btn-info btn-sm border" title="Alterar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('delete-testimonial')
                                <td class='py-2 px-2'>
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.testimonials.delete', ['id' => $testimonial->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-block">
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
            @if ($testimonials)
                <div class="px-2 pt-3">
                    {{ $testimonials->links('pagination::bootstrap-5') }}
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
