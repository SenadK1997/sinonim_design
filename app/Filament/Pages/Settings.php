<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Postavke sajta';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'brand_name' => Setting::get('brand_name', 'SinonimDesign'),
            'tagline' => Setting::get('tagline', 'Ručno rađena kolekcija'),
            'hero_mode' => Setting::get('hero_mode', 'image'),
            'hero_headline' => Setting::get('hero_headline'),
            'hero_subheadline' => Setting::get('hero_subheadline'),
            'hero_cta_label' => Setting::get('hero_cta_label', 'Pogledaj kolekciju'),
            'hero_cta_url' => Setting::get('hero_cta_url', '/kolekcije'),
            'hero_gradient_from' => Setting::get('hero_gradient_from', '#efe7de'),
            'hero_gradient_to' => Setting::get('hero_gradient_to', '#c9a892'),
            'hero_image_path' => Setting::get('hero_image_path'),

            'banner_enabled' => (bool) Setting::get('banner_enabled', false),
            'banner_text' => Setting::get('banner_text'),
            'banner_url' => Setting::get('banner_url'),
            'banner_bg' => Setting::get('banner_bg', '#1a1a1a'),
            'banner_fg' => Setting::get('banner_fg', '#ffffff'),

            'contact_email' => Setting::get('contact_email'),
            'contact_phone' => Setting::get('contact_phone'),
            'whatsapp_number' => Setting::get('whatsapp_number'),
            'viber_number' => Setting::get('viber_number'),
            'instagram_handle' => Setting::get('instagram_handle', 'sinonim_design'),
            'facebook_url' => Setting::get('facebook_url'),
            'tiktok_url' => Setting::get('tiktok_url'),

            'shipping_flat_rate' => Setting::get('shipping_flat_rate', 5),
            'shipping_free_over' => Setting::get('shipping_free_over'),
            'shipping_note' => Setting::get('shipping_note'),

            'about_text' => Setting::get('about_text'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([

                Tab::make('Brend')->icon(Heroicon::OutlinedSparkles)->schema([
                    Section::make()->columns(2)->schema([
                        TextInput::make('brand_name')->label('Naziv brenda')->required(),
                        TextInput::make('tagline')->label('Kratki opis (tagline)'),
                        Textarea::make('about_text')->label('O nama — kratki tekst')->rows(4)->columnSpanFull(),
                    ]),
                ]),

                Tab::make('Naslovna (hero)')->icon(Heroicon::OutlinedPhoto)->schema([
                    Section::make()->columns(2)->schema([
                        Select::make('hero_mode')
                            ->label('Način prikaza hero sekcije')
                            ->options([
                                'image' => 'Slika',
                                'gradient' => 'Gradient (bez slike)',
                                'none' => 'Bez hero sekcije',
                            ])
                            ->native(false)
                            ->live()
                            ->required(),

                        FileUpload::make('hero_image_path')
                            ->label('Hero slika')
                            ->image()
                            ->directory('hero')
                            ->visibility('public')
                            ->visible(fn ($get) => $get('hero_mode') === 'image')
                            ->columnSpanFull(),

                        ColorPicker::make('hero_gradient_from')
                            ->label('Gradient — početna boja')
                            ->visible(fn ($get) => $get('hero_mode') === 'gradient'),
                        ColorPicker::make('hero_gradient_to')
                            ->label('Gradient — završna boja')
                            ->visible(fn ($get) => $get('hero_mode') === 'gradient'),

                        TextInput::make('hero_headline')->label('Naslov')->columnSpanFull(),
                        Textarea::make('hero_subheadline')->label('Podnaslov')->rows(2)->columnSpanFull(),
                        TextInput::make('hero_cta_label')->label('Tekst dugmeta'),
                        TextInput::make('hero_cta_url')->label('Link dugmeta'),
                    ]),
                ]),

                Tab::make('Traka na vrhu (banner)')->icon(Heroicon::OutlinedMegaphone)->schema([
                    Section::make()->columns(2)->schema([
                        Toggle::make('banner_enabled')->label('Prikaži banner')->columnSpanFull(),
                        TextInput::make('banner_text')->label('Tekst banera')->columnSpanFull(),
                        TextInput::make('banner_url')->label('Link (opcionalno)'),
                        ColorPicker::make('banner_bg')->label('Pozadinska boja'),
                        ColorPicker::make('banner_fg')->label('Boja teksta'),
                    ]),
                ]),

                Tab::make('Kontakt & društvene mreže')->icon(Heroicon::OutlinedPhone)->schema([
                    Section::make()->columns(2)->schema([
                        TextInput::make('contact_email')->label('E-mail za kontakt')->email(),
                        TextInput::make('contact_phone')->label('Telefon')->tel(),
                        TextInput::make('whatsapp_number')->label('WhatsApp broj')->helperText('Sa pozivnim, bez razmaka. Npr. 38761234567'),
                        TextInput::make('viber_number')->label('Viber broj')->helperText('Isti format kao WhatsApp.'),
                        TextInput::make('instagram_handle')->label('Instagram username')->prefix('@'),
                        TextInput::make('facebook_url')->label('Facebook URL'),
                        TextInput::make('tiktok_url')->label('TikTok URL'),
                    ]),
                ]),

                Tab::make('Dostava')->icon(Heroicon::OutlinedTruck)->schema([
                    Section::make()->columns(2)->schema([
                        TextInput::make('shipping_flat_rate')
                            ->label('Cijena dostave (KM)')
                            ->numeric()
                            ->suffix('KM')
                            ->step(0.01)
                            ->default(5),
                        TextInput::make('shipping_free_over')
                            ->label('Besplatna dostava iznad (KM)')
                            ->numeric()
                            ->suffix('KM')
                            ->step(0.01)
                            ->helperText('Ostavi prazno da isključiš besplatnu dostavu.'),
                        Textarea::make('shipping_note')
                            ->label('Napomena o dostavi (prikazuje se u korpi)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                ]),

            ])->columnSpanFull(),
        ])->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Sačuvaj postavke')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Postavke su sačuvane')
            ->success()
            ->send();
    }
}
