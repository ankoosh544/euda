<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MapSearchForm extends Component
{
    public $address;

    public function render()
    {
        return view('livewire.map-search-form');
    }

    public function search()
    {
        // Handle the search functionality here
    }
}
