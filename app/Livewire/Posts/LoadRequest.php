<?php

namespace App\Livewire\Posts;

use App\Repository\IPostRepository;
use App\Service\IWellService;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

class LoadRequest extends Component
{
    use WithPagination;
    private IWellService $service;
    public ?string $idsWellName = null;

    public function booted(IWellService $service): void
    {
        $this->service = $service;
    }

    public function mount(?string $idsWellName = null)
    {
        $this->idsWellName = $idsWellName;
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $posts = $this->service->pagedWellPost(true, $this->idsWellName);

        $initialData = [
            'posts' => $posts, 'idsWellName' => $this->idsWellName
        ];
        return view('livewire.post.index', $initialData)
            ->with('i', $posts->perPage() * $this->getPage());
    }
}
