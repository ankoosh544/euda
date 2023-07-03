@if ($status == 'active')
    <div class="relative flex items-center bg-green-100 px-4 py-1 rounded-md text-sm font-semibold text-green-800 w-fit">
        <span class="relative flex h-3 w-3 mr-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        </span>
        The system works properly
    </div>
@elseif($status == 'warning')
    <div
        class="relative flex items-center bg-yellow-100 px-4 py-1 rounded-md text-sm font-semibold text-yellow-800 w-fit">
        <span class="relative flex h-3 w-3 mr-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
        </span>
        Warning! There could be a problem in the system
    </div>
@else
    <div class="relative flex items-center bg-red-100 px-4 py-1 rounded-md text-sm font-semibold text-red-800 w-fit">
        <span class="relative flex h-3 w-3 mr-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
        </span>
        Watchout! A problem has been detected in the system
    </div>
@endif
@if($last_communication)
<div class="relative flex items-center  px-4 py-1 rounded-md text-sm font-semibold text-black-800 w-fit">
        <span class="relative flex h-3 w-3 mr-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full "></span>
            <span class="relative inline-flex rounded-full h-3 w-3"></span>
        </span>
   <label for="">Date</label>:  {{$last_communication}}
    </div>

@endif
