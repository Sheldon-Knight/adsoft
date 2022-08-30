<?php

namespace App\Filament\Resources\InstructionResource\Widgets;

use App\Models\Comment;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class Comments extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.instruction-resource.widgets.comments';

    protected int | string | array $columnSpan = 'full';

    public Model $record;

    public $currentRouteName;

    public $comment;

    public $reply;

    protected $commmentRules = [
        'comment' => 'required',
    ];

    public static function canView(): bool
    {

        if (cache()->get('hasExpired') == true) {
            return false;
        };
        
        if (cache()->get('current_plan') == "Basic") {
            return false;
        }

        return true;
    }

    public function mount()
    {
        $this->currentRouteName = url()->current();
    }

    public function addComment()
    {
        $this->validate($this->commmentRules);

        $this->record->comment($this->comment, user: auth()->user());

        Notification::make()
            ->title('Comment Added')
            ->success()
            ->send();

        $this->comment = '';

        return redirect()->to($this->currentRouteName);
    }
}
