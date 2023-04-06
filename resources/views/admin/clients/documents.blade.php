<strong>Modelos de documentos a anexar:</strong>
<ul>
    @forelse ($documents as $document)
        @if (isset($document->file))
            <li><a href="{{Storage::url($document->file)}}" target="blank">{{$document->title}}</a></li>
        @else
            <li>{{$document->title}}</li>
        @endif
    @empty
        <li>Nenhum modelo de documento adicionado.</li>
    @endforelse
</ul>