<div class="flex xl:flex-row flex-col gap-5 justify-between">

    <div class="h-80 xl:basis-4/12 rounded-xl bg-white p-4 shadow dark:border-gray-600 dark:bg-gray-700 relative">
        @include(
            'filament.resources.plant-resource.pages.components.dashboard.body.components.floor-calls',
            [
                'floorChart' => $floorChart,
            ]
        )
    </div>

    <div class="xl:basis-1/2 flex flex-col gap-3 xl:gap-1 justify-between">
        <div class="flex xl:flex-row flex-col gap-3">

            @include('filament.resources.plant-resource.pages.components.dashboard.body.components.out-of-service')

            @include('filament.resources.plant-resource.pages.components.dashboard.body.components.safety-test-panel')

        </div>

        @include(
            'filament.resources.plant-resource.pages.components.dashboard.body.components.floor-status',
            ['data' => $data]
        )

    </div>
    <div class="xl:basis-2/12 flex flex-col gap-3 xl:gap-1 justify-between">

        @include('filament.resources.plant-resource.pages.components.dashboard.body.components.mileage', [
            'data' => $data,
        ])
        @include('filament.resources.plant-resource.pages.components.dashboard.body.components.mileage', [
            'data' => $data,
        ])
        @include('filament.resources.plant-resource.pages.components.dashboard.body.components.mileage', [
            'data' => $data,
        ])

    </div>
</div>
