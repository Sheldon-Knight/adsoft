<div class="grid grid-cols-1 gap-6 filament-forms-component-container"
    @if ($comment->parent_id != null) style="margin-left:40px;" @endif>
    <div class="col-span-full">
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 filament-forms-component-container">
            <div class=" col-span-1     ">
                <div class="filament-forms-field-wrapper">
                    <div class="space-y-2">
                        <form wire:submit.prevent="addReply">

                            <div
                                class="flex items-center space-x-2 rtl:space-x-reverse group filament-forms-text-input-component mb-2">
                                <div class="flex-1">

                                    <blockquote class="relative">

                                        <div class="relative z-10">
                                            <p class="text-gray-800 dark:text-white"><em>
                                                    <small>message:</small> {{ $comment->content }}
                                            </p></em>
                                        </div>
                                    </blockquote>

                                    <hr>
                                </div>
                            </div>
                            <small>

                                <i> {{ $comment->user->name }}
                                    @if ($comment->parent_id != null)
                                        Replied :
                                    @else
                                        Commented :
                                    @endif
                                    {{ $comment->created_at->diffForHumans() }}
                                </i>

                            </small>
                            <div
                                class="flex items-center space-x-2 rtl:space-x-reverse group filament-forms-text-input-component mb-2">
                                <div class="flex-1">
                                    <input type="text" wire:model.defer="reply" dusk="filament.forms.reply"
                                        id="reply"
                                        class="block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:focus:border-primary-600 border-gray-300 dark:border-gray-600">
                                    @error('reply')
                                        <p class="text-sm text-danger-600 filament-forms-field-wrapper-error-message">
                                            {{ $message }}.
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            <x-filament::button type="submit" dusk="filament.forms.submit"
                                class="filament-forms-submit-button">
                                Reply
                            </x-filament::button>
                            <x-filament::button wire:click.defer="delete"
                                style="background-color:rgb(225 29 72 / var(--tw-text-opacity));">
                                Delete @if ($comment->parent_id != null)
                                    Reply
                                @else
                                    Comment
                                @endif
                            </x-filament::button>
                        </form>
                        @include('partials.replies', ['comments' => $comment->replies])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
