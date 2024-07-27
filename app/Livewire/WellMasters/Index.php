<?php

namespace App\Livewire\WellMasters;

use App\Models\WellMaster;
use App\Repository\IWellMasterRepository;
use App\Service\IWellService;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    private IWellService $service;

    #[Validate('required|string|min:2')]
    public $querySearch;

    public function booted(IWellService $service): void
    {
        $this->service = $service;
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $wellMasters = $this->service->pagedWellMaster($this->querySearch);

        return view('livewire.well-master.index', compact('wellMasters'))
            ->with('i', $this->getPage() * $wellMasters->perPage());
    }

    public function search(): void
    {
        $this->service->pagedWellMaster($this->querySearch);
    }

    public function delete(WellMaster $wellMaster)
    {
        $wellMaster->delete();

        return $this->redirectRoute('well-masters.index', navigate: true);
    }
}