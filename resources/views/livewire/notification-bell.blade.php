<div class="flex">
  @unlessrole('Client')
<a href={{ route('filament.pages.my-instructions') }}
            class="flex items-center justify-center gap-3 px-3 py-2 rounded-lg font-medium transition hover:bg-gray-500/5 focus:bg-gray-500/5 dark:text-gray-300 dark:hover:bg-gray-700">

            <svg class="h-5 w-5 shrink-0" fill="#EAB435" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z">
                </path>
            </svg>
            <div class="flex flex-1">
                <span class="mr-2">
                    My Instructions
                </span>
                <span
                    class="inline-flex items-center justify-center ml-auto rtl:ml-0 rtl:mr-auto min-h-4 px-2 py-0.5 text-xs font-medium tracking-tight rounded-xl whitespace-normal text-primary-700 bg-primary-500/10 dark:text-primary-500">
                    {{ $myInstructions }}
                </span>
            </div>
        </a>

        <a href={{ route('filament.pages.my-jobs') }}
            class="flex items-center justify-center gap-3 px-3 py-2 rounded-lg font-medium transition hover:bg-gray-500/5 focus:bg-gray-500/5 dark:text-gray-300 dark:hover:bg-gray-700">

            <svg class="h-5 w-5 shrink-0" fill="#EAB435" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                    clip-rule="evenodd"></path>
                <path
                    d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z">
                </path>
            </svg>
            <div class="flex flex-1">
                <span class="mr-2">
                    My Jobs
                </span>
                <span
                    class="inline-flex items-center justify-center ml-auto rtl:ml-0 rtl:mr-auto min-h-4 px-2 py-0.5 text-xs font-medium tracking-tight rounded-xl whitespace-normal text-primary-500 bg-primary-500/10 dark:text-primary-500">
                    {{ $myJobs }}
                </span>
            </div>
        </a>


  @else

  @endrole
</div>
