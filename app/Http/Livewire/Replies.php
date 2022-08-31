<?php

namespace App\Http\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;

class Replies extends Component
{
    public $comment;

    public $reply;

    public $currentRouteName;

    public function mount()
    {
        $this->currentRouteName = url()->current();
    }

    public function render()
    {
        return view('livewire.replies');
    }

    protected $replyRules = [
        'reply' => 'required',
    ];

    public function addReply()
    {
        $this->validate($this->replyRules);

        $modelName = '\\'.$this->comment->commentable_type;

        $model = $modelName::find($this->comment->commentable_id);

        $model->comment($this->reply, user: auth()->user(), parent: $this->comment);

        Notification::make()
            ->title('Reply Added')
            ->success()
            ->send();

        $this->reply = '';

        return redirect()->to($this->currentRouteName);
    }

    public function delete()
    {
        $this->comment->delete();

        Notification::make()
        ->title('Deleted Succesfully')
        ->success()
        ->send();

        return redirect()->to($this->currentRouteName);
    }
}
