<?php

namespace App\Filament\Resources\PlantResource\Pages;

use App\Filament\Resources\PlantResource;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Storage;

class ViewPlant extends EditRecord
{
    protected static string $resource = PlantResource::class;

    protected static string $view = 'filament.resources.plant-resource.pages.view-plant';

    public $status;
    public $awsData;
    public $selectedDate;
    public $totalOutService;
    public $lastOutService;
    public $lastDoorFault;
    public $totalFSR;
    public $lastFSR;

    protected function getHeader(): View
    {
        return view('filament.resources.plant-resource.pages.components.header', ['plant' => $this->getRecord()]);
    }

    public function mount($record): void
    {
        parent::mount($record);
        // Initializa variable
        $this->form->fill([
            'date' => Carbon::today(),
        ]);
        $this->selectedDate = Carbon::today()->format("Y-m-d");
        $this->initVariable();
    }

    protected function getFormSchema(): array
    {
        // Setting data picker
        return [
            DatePicker::make('date')->label('')->reactive()
            ->afterStateUpdated(function (Closure $set, $state) {
                $this->selectedDate = Carbon::parse($state)->format("Y-m-d");
                //error_log($this->selectedDate);
                $this->goToDate();
            }),
        ];
    }

    public function getData()
    {
        // Get files list from AWS S3
        $contents = Storage::disk('s3')->allFiles('datastore/'.$this->record->datastore.'/__dt='.$this->selectedDate.' 00:00:00/');
        // $contents = Storage::disk('s3')->allFiles('datastore/mcallinn_data/__dt=2023-06-21 00:00:00/');
        // error_log(empty($contents));
        //error_log(implode(" - ", $contents));

        // Check if folder is empty
        if(empty($contents))
            return 0;

        foreach ($contents as $k => $file) {
            // Get file from S3
            $s3_file = Storage::disk('s3')->get($file);
            // Get file date
            $fileDate = Carbon::createFromTimestamp(Storage::disk('s3')->lastModified($file))->toDateTimeString();
            // Unzip and decode file 
            $string = gzdecode($s3_file);
            $data = json_decode($string);
            // Copying first element in $awsObject and then update it based on the data from the other elements
            if ($k == 0)
                $awsObject = $data;
            else {
                // Fill $awsObject with file data
                $awsObject->fOutOfService = $data->fOutOfService;
                if($awsObject->fOutOfService)
                    $this->lastOutService = $fileDate;
                $this->totalOutService += $data->fOutOfService;

                $awsObject->cOutOfService = $data->cOutOfService;

                $awsObject->countKM += $data->countKM;

                $awsObject->fSTR = $data->fSTR;
                if($awsObject->fSTR)
                    $this->lastFSR = $fileDate;
                $this->totalFSR += $data->fSTR;

                foreach ($data->floors as $key => $f) {
                    $awsObject->floors->$key += $f;

                    // If is last cycle check doorFault
                    if(count($contents) == ($k+1) && $data->doorsFaultStatus->$key == 2){
                        $awsObject->doorsFaultStatus->$key = 2;
                        $this->lastDoorFault = $fileDate;
                    } else if($data->doorsFaultStatus->$key == 1)
                        $awsObject->doorsFaultStatus->$key = 1;

                }
            }
        }

        return json_encode($awsObject);
    }

    public function statusCheck($awsData) {
        // Check system status
        $awsData = json_decode($awsData);
        if($awsData->fOutOfService)
            return 'fault';
        // else if($awsData->fOutOfService >= 1)
        //     return 'warning';
        else
            return 'active';
    }

    public function goToDate() {
        $this->initVariable();
    }

    public function initVariable() {
        $this->totalOutService = 0;
        $this->lastOutService = 'No out of service';
        $this->totalFSR = 0;
        $this->lastFSR = 'No tests';
        $this->lastDoorFault = 'No door fault';
        $this->awsData = $this->getData();
        if($this->awsData)
            $this->status = $this->statusCheck($this->awsData);
        else
            Self::$view = 'filament.resources.plant-resource.pages.components.no-file-found';
    }
}