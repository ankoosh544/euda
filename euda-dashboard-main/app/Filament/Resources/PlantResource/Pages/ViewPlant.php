<?php

namespace App\Filament\Resources\PlantResource\Pages;
use Illuminate\Support\Facades\Cache;


use App\Filament\Resources\PlantResource;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Storage;
use App\Models\Plant;
use App\Models\Message;

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
    public $lastCommunicationTime;

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
        

        $messagesData = Message::with('floorcall', 'doorfault')
        ->where('plant_id', $this->record->id)
        ->whereDate('created_at', $this->selectedDate)
        ->get()
        ->toJson();
        $messages = json_decode($messagesData);
        if(empty($messages))
            return 0;
        foreach ($messages as $k => $data) {
           
            // Get file date
            //$fileDate = Carbon::createFromTimestamp(Storage::disk('s3')->lastModified($file), 'Europe/Rome')->toDateTimeString();
            if ($k == 0){
                $awsObject = $data;
               // dd($awsObject);die();
            }
            else {
                // Fill $awsObject with file data
                $awsObject->fOutOfService = $data->fOutOfService;
                if($awsObject->fOutOfService)
                    $this->lastOutService = $data->created_at;
                $this->totalOutService += $data->fOutOfService;

                $awsObject->cOutOfService = $data->cOutOfService;

                $awsObject->countKM += $data->countKM;

                $awsObject->fSTR = $data->fSTR;
                if($awsObject->fSTR)
                    $this->lastFSR = $data->updated_at;
                $this->totalFSR += $data->fSTR;

                foreach ($data->floorcall as $key => $floorcall) {
                    $floor_value = $floorcall->floor_value;
                    $awsObject->floorcall[$key]->floor_value += $floor_value;
                   // If it's the last cycle, check doorFault
                   if (count($messages) == ($k + 1) && $data->doorfault[$key]->doorfault_value == 2) {
                    $awsObject->doorfault[$key]->doorfault_value = 2;
                    $this->lastDoorFault = $fileDate;
                } else if ($data->doorfault[$key]->doorfault_value == 1) {
                    $awsObject->doorfault[$key]->doorfault_value = 1;
                }

                }
            }
        }
       // dd($awsObject);die();
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