@props(['id','headers'=>[]])
<table id="{{ $id }}" class="table table-striped table-hover">
    <thead>
        <tr>
            @foreach($headers as $h)
                <th>{{ $h }}</th>
            @endforeach
        </tr>
    </thead>
</table>

