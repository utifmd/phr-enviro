<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use App\Repository\IUserRepository;
use App\Utils\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    private IUserRepository $userRepository;
    public UserForm $form;
    public string $currentUserId;
    public string $currentUserRole;

    public function mount(User $user)
    {
        $this->currentUserId = $user->id;
        $this->currentUserRole = $user->role;
        $this->form->isUpdating = true;
        $this->form->setUserRequestModel($user);
    }

    public function booted(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function save(): void
    {
        $this->form->update(function (array $request){
            try {
                $this->userRepository->update($this->currentUserId, $request);
                $this->redirectRoute('users.index', navigate: true);

            } catch (\Throwable $throwable){
                Log::debug($throwable->getMessage());
                $this->form->addError('email', 'Email or username has been taken.');
            }
        });
    }

    public function toggleIsNewPassword(): void
    {
        $this->form->isNewPassword = !$this->form->isNewPassword;
        if ($this->form->isNewPassword) {
            $this->form->reset('password');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.user.edit');
    }
}
