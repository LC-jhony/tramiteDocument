 {{-- <x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
     <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
  
     </div>
 </x-dynamic-component> --}}
 <div class="w-full h-full border-rose-500 rounded-lg">
     <iframe class="w-full min-h-full" src="{{ asset($getRecord()->file) }}" frameborder="0"
         allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
         allowfullscreen>
     </iframe>

 </div>
