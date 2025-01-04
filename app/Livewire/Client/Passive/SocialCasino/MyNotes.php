<?php

namespace App\Livewire\Client\Passive\SocialCasino;

use App\Models\SocialCasino;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class MyNotes extends Component implements HasForms
{
    use InteractsWithForms;

    #[Locked]
    public SocialCasino $socialCasino;

    #[Validate('nullable|string|max:2000')]
    public ?string $notes = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function updateNotes(): void
    {
        auth()->user()->getSocialCasino($this->socialCasino)->update(['notes' => $this->notes ?? null]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                RichEditor::make('notes')
                    ->label('')
                    ->default(auth()->user()->getSocialCasino($this->socialCasino)->notes)
                    ->maxLength(2000),
            ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.client.passive.social-casinos.partials.my-notes');
    }
}
