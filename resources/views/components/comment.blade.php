<x-filament::widget>
    <x-filament::card>
        <div
            class="flex items-center px-4 py-2 bg-gray-100 rtl:space-x-reverse overflow-hidden rounded-t-xl min-h-[56px] filament-forms-section-header-wrapper dark:bg-gray-900">
            <div class="flex-1 filament-forms-section-header">
                <h3 class="text-xl font-bold tracking-tight pointer-events-none">
                    Display Comments
                </h3>
            </div>
        </div>

 @include('partials.replies', ['comments' => $this->record->comments])

<form wire:submit.prevent="addComment">
            <div class="grid grid-cols-1 gap-6 filament-forms-component-container">
                <div class=" col-span-full     ">
                    <div class="grid grid-cols-1   lg:grid-cols-1   gap-6 filament-forms-component-container">
                        <div class=" col-span-1     ">
                            <div class="filament-forms-field-wrapper">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                                        <label class="inline-flex items-center space-x-3 rtl:space-x-reverse filament-forms-field-wrapper-label" for="comment">
                                            <span class="text-sm font-medium leading-4 text-gray-700 dark:text-gray-300">
                                                Comment
                                            </span>
                                        </label>


                                    </div>
                                    <textarea id="comment" dusk="filament.forms.comment" wire:model.defer="comment" required class="block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70 filament-forms-textarea-component dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-primary-600 border-gray-300" x-data="textareaFormComponent()" x-on:input="render()" style="height: 150px">
                                </textarea>

                                    @error('comment')
                                    <p class="text-sm text-danger-600 filament-forms-field-wrapper-error-message">
                                        {{ $message }}.
                                    </p>
                                    @enderror


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <x-filament::button type="submit" dusk="filament.forms.submit" class="filament-forms-submit-button" style="background-color: green!important">
                Comment
            </x-filament::button>
            
        </form>

    </x-filament::card>
</x-filament::widget>
