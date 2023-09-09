<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use App\Models\Todo;
use Log;
class Todos extends Component
{
    use WithPagination;
    #[Rule('required|min:3|max:100')]
    public $name;

    public $search;

    public $editingTodoID;

    #[Rule('required|min:3|max:100')]
    public $editingTodoName;

    public function create(){
        //validate
        $validated = $this->validateOnly('name');

        //create
        Todo::create($validated);

        //reset input
        $this->reset('name');

        //session
        session()->flash('suc','Created Successfully!');

        $this->resetPage();
    }

    public function delete($id){
        try{
            Todo::findOrFail($id)->delete();
            //session
            session()->flash('suc','Deleted Successfully!');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            //session
            session()->flash('fail','Something went error!');
            return;
        }

    }

    public function updateStatus($id)
    {
        try{
            $todo = Todo::findOrFail($id);
            $todo->update(['completed'=>$todo->completed ==1 ? 0:1]);
            //session
            session()->flash('suc','status changed Successfully!');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            //session
            session()->flash('fail','Something went error!');
            $this->cancel();
            return;
        }
    }

    public function edit($id){
        try{
            $this->editingTodoID = $id;
            $this->editingTodoName =  Todo::findOrFail($id)->name;
        }catch(\Exception $e){
            Log::error($e->getMessage());
            //session
            session()->flash('fail','Something went error!');
            $this->cancel();
            return;
        }
    }

    public function updateTodo($id){
        $this->validateOnly('editingTodoName');

        try{

            Todo::findOrFail($this->editingTodoID)->update(['name'=>$this->editingTodoName]);
            //session
            session()->flash('suc','name updated Successfully!');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            //session
            session()->flash('fail','Something went error!');
            return;
        }
        $this->cancel();
    }

    public function cancel()
    {
        $this->reset('editingTodoID','editingTodoName');
    }

    public function render()
    {
        $todos = Todo::latest()->where('name','like',"%{$this->search}%")->paginate(5);
        return view('livewire.todos',['todos'=>$todos]);
    }
}
