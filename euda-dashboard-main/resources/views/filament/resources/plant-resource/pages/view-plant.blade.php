@php
    $data = json_decode($awsData);
    $floorChart = new \Asantibanez\LivewireCharts\Models\RadarChartModel();
    
    $actualFloor = 0;

    foreach ($data->floorcall as $floor) {
        $floorChart->addSeries('Calls', 'Floor ' . $actualFloor, $floor->floor_value);
        $actualFloor++;
    }
@endphp
<x-filament::page :widget-data="['record' => $record]">
    <div class="plant-view rounded-xl bg-gray-100 shadow dark:bg-gray-800 dark:text-gray-100">
        <div
            class="w-full p-4 rounded-t-xl bg-white flex xl:flex-row flex-col gap-3 xl:justify-between border-b border-b-slate-200 dark:bg-gray-700 dark:border-gray-600">
            
            @include('filament.resources.plant-resource.pages.components.dashboard.header.plant-info', ['record' => $record])
            
            <div class="flex flex-col justify-between gap-3 items-end">

                @include('filament.resources.plant-resource.pages.components.dashboard.header.plant-status', ['status' => $status, 'last_communication' => $data->created_at])

                @include('filament.resources.plant-resource.pages.components.dashboard.header.plant-date')

            </div>
        </div>
        <div class="w-full p-4">

            @include('filament.resources.plant-resource.pages.components.dashboard.body.body', 
            [
                'floorChart' => $floorChart,
                'data' => $data,
            ])
            
        </div>
    </div>
    @livewireChartsScripts
</x-filament::page>
