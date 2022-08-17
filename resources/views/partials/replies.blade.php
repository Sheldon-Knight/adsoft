@foreach ($comments as $comment)
    @livewire('replies',['comment' => $comment])
@endforeach
