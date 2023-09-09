<div>
    @include('livewire.includes.create-todo-box')
    @include('livewire.includes.search')
    @if(session('suc'))
    <span class="text-green-500 text-xs">{{session('suc')}}</span>
    @endif
    @if(session('fail'))
        <span class="red-green-500 text-xs">{{session('fail')}}</span>
    @endif
    <div id="todos-list">
        @if(count($todos) == 0)
            <div class="mx-auto" style="text-align:center; padding-top:50px;">
                <h1 class="text-xl ">Nothing To Show</h1>
            </div>
        @else
            @foreach($todos as $todo)
                @include('livewire.includes.todo-card')
            @endforeach
            <div class="my-2">
                {{$todos->links()}}
            </div>
        @endif
    </div>
</div>
