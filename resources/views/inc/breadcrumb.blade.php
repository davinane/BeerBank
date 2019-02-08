<div class="container-fluid">
  @if(Request::is('todo/*'))
  @isset($todo)
    @if(Request::is('todo/*/edit'))
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Todos</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="/todo/{{$todo->id}}">{{$todo->id}}</a></li>
      </ol>
    </nav>
    @else
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Todos</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{$todo->id}}</li>
      </ol>
    </nav>
    @endif
  @endisset
  @endif
</div>
