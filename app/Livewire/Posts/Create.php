<?php

namespace App\Livewire\Posts;

use App\Livewire\Forms\PostForm;
use App\Models\Post;
use App\Models\WellMaster;
use App\Repository\IUserRepository;
use App\Service\IWellService;
use App\Utils\Enums\PostTypeEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;
    private ?IWellService $service;
    public PostForm $form;

    #[Validate('required|image|max:2048')]
    public $imageFile;

    public function mount(Post $post): void
    {
        if($wellMaster = Session::get(WellMaster::WELL_MASTER_NAME)) {
            $post->title = $wellMaster->ids_wellname;
            $post->desc = $wellMaster->field_name.';'.$wellMaster->ids_wellname.';'.$wellMaster->well_number.';'.$wellMaster->legal_well;
            $post->type = PostTypeEnum::POST_WELL_TYPE->value;
        }
        $this->form->setRequestPostModel($post);
    }
    public function booted(IWellService $service, IUserRepository $userRepository): void
    {
        $this->service = $service;
        if($wellMaster = Session::get(WellMaster::WELL_MASTER_NAME)){
            $this->form->user_id = $userRepository->authenticatedUser()->id;
            $this->form->ids_wellname = $wellMaster->ids_wellname;
        }
    }
    public function onAddLoadTimePressed(): void
    {
        $this->form->pushLoadedDatetime();
    }

    public function onRemoveLoadTimePressed(int $i): void
    {
        $this->form->filteredLoadedDatetimeAt($i);
    }

    public function save()
    {
        $this->form->store(function (
            $post, $uploadedUrl, $workOrders){
            // if (is_null($this->imageFile)) return;
            $path = "images/". $this->form->user_id ."/";
            $fileName = $path . date('YmdHis') .".". $this->imageFile->getClientOriginalExtension();

            $this->imageFile->storeAs('public', $fileName);
            $uploadedUrl['path'] = "storage/".$fileName;
            $uploadedUrl['url'] = URL::asset($uploadedUrl['path']);

            $this->service->addNewWell($post, $uploadedUrl, $workOrders);

            Session::remove(WellMaster::WELL_MASTER_NAME);
        });
        return $this->redirectRoute('posts.index', navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.post.create');
    }
}
