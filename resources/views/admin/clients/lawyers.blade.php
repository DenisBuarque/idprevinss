<small class="d-block mt-2">Advogado(s) do franqueado:</small>
@foreach ($lawyers as $lawyer)
    @if (!empty($lawyer->name))
        <span class="d-block"><input type="checkbox" name="lawyer[]" value="{{ $lawyer->id }}" checked> {{ $lawyer->name }}</span>
    @else
        <span class="d-block">Não há advogados cadastrado para esse franqueado.</span>
    @endif
@endforeach