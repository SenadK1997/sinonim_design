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

    protected static ?string $title = 'Postavke sajta';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // Brand
            'brand_name' => Setting::get('brand_name', 'SinonimDesign'),
            'tagline' => Setting::get('tagline', 'Ručno rađena kolekcija'),
            'about_text' => Setting::get('about_text'),

            // Hero
            'hero_mode' => Setting::get('hero_mode', 'gradient'),
            'hero_headline' => Setting::get('hero_headline'),
            'hero_subheadline' => Setting::get('hero_subheadline'),
            'hero_cta_label' => Setting::get('hero_cta_label', 'Pogledaj kolekciju'),
            'hero_cta_url' => Setting::get('hero_cta_url', '/kolekcije'),
            'hero_gradient_from' => Setting::get('hero_gradient_from', '#efe7de'),
            'hero_gradient_to' => Setting::get('hero_gradient_to', '#c9a892'),
            'hero_image_path' => Setting::get('hero_image_path'),

            // Banner
            'banner_enabled' => (bool) Setting::get('banner_enabled', false),
            'banner_text' => Setting::get('banner_text'),
            'banner_url' => Setting::get('banner_url'),
            'banner_bg' => Setting::get('banner_bg', '#1a1a1a'),
            'banner_fg' => Setting::get('banner_fg', '#ffffff'),

            // Contact
            'contact_email' => Setting::get('contact_email'),
            'contact_phone' => Setting::get('contact_phone'),
            'whatsapp_number' => Setting::get('whatsapp_number'),
            'viber_number' => Setting::get('viber_number'),
            'instagram_handle' => Setting::get('instagram_handle', 'sinonim_design'),
            'facebook_url' => Setting::get('facebook_url'),
            'tiktok_url' => Setting::get('tiktok_url'),

            // Shipping
            'shipping_flat_rate' => Setting::get('shipping_flat_rate', 5),
            'shipping_free_over' => Setting::get('shipping_free_over'),
            'shipping_note' => Setting::get('shipping_note'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([

                Tab::make('Brend & O nama')->icon(Heroicon::OutlinedSparkles)->schema([
                    Section::make('Osnovni podaci brenda')
                        ->description('Ovi podaci se koriste svugdje na sajtu (naslov, footer, meta oznake).')
                        ->columns(2)
                        ->schema([
                            TextInput::make('brand_name')
                                ->label('Naziv brenda')
                                ->required()
                                ->maxLength(60),
                            TextInput::make('tagline')
                                ->label('Kratki opis (tagline)')
                                ->maxLength(120)
                                ->helperText('Prikazuje se ispod logotipa i u pretraživačima.'),
                            Textarea::make('about_text')
                                ->label('O nama — tekst za naslovnu i O nama stranicu')
                                ->rows(5)
                                ->columnSpanFull()
                                ->helperText('Prikazuje se u sekciji "O brendu" na naslovnoj i na stranici /o-nama.'),
                        ]),
                ]),

                Tab::make('Naslovna (hero)')->icon(Heroicon::OutlinedPhoto)->schema([
                    Section::make('Hero sekcija')
                        ->description('Glavni banner na vrhu naslovne stranice.')
                        ->columns(2)
                        ->schema([
                            Select::make('hero_mode')
                                ->label('Način prikaza')
                                ->options([
                                    'image' => '🖼  Slika',
                                    'gradient' => '🎨  Gradient (bez slike)',
                                    'none' => '⊘  Bez hero sekcije',
                                ])
                                ->native(false)
                                ->live()
                                ->required()
                                ->columnSpanFull(),

                            FileUpload::make('hero_image_path')
                                ->label('Hero slika')
                                ->image()
                                ->imageEditor()
                                ->directory('hero')
                                ->visibility('public')
                                ->maxSize(4096)
                                ->helperText('Preporučeno: široka slika, min. 1920×1080 px.')
                                ->visible(fn ($get) => $get('hero_mode') === 'image')
                                ->columnSpanFull(),

                            ColorPicker::make('hero_gradient_from')
                                ->label('Gradient — početna boja')
                                ->visible(fn ($get) => $get('hero_mode') === 'gradient'),
                            ColorPicker::make('hero_gradient_to')
                                ->label('Gradient — završna boja')
                                ->visible(fn ($get) => $get('hero_mode') === 'gradient'),

                            TextInput::make('hero_headline')
                                ->label('Glavni naslov')
                                ->maxLength(80)
                                ->columnSpanFull()
                                ->helperText('Veliki tekst na hero sekciji.')
                                ->visible(fn ($get) => $get('hero_mode') !== 'none'),

                            Textarea::make('hero_subheadline')
                                ->label('Podnaslov')
                                ->rows(2)
                                ->maxLength(200)
                                ->columnSpanFull()
                                ->visible(fn ($get) => $get('hero_mode') !== 'none'),

                            TextInput::make('hero_cta_label')
                                ->label('Tekst dugmeta')
                                ->maxLength(40)
                                ->helperText('npr. "Pogledaj kolekciju"')
                                ->visible(fn ($get) => $get('hero_mode') !== 'none'),
                            TextInput::make('hero_cta_url')
                                ->label('Link dugmeta')
                                ->helperText('npr. /kolekcije ili /prodavnica')
                                ->visible(fn ($get) => $get('hero_mode') !== 'none'),
                        ]),
                ]),

                Tab::make('Traka na vrhu')->icon(Heroicon::OutlinedMegaphone)->schema([
                    Section::make('Announcement banner')
                        ->description('Uska traka na samom vrhu sajta — za obavještenja o sniženjima, praznicima, itd.')
                        ->columns(2)
                        ->schema([
                            Toggle::make('banner_enabled')
                                ->label('Prikaži traku')
                                ->helperText('Uključi/isključi bez brisanja teksta.')
                                ->columnSpanFull(),
                            TextInput::make('banner_text')
                                ->label('Tekst')
                                ->maxLength(140)
                                ->columnSpanFull()
                                ->helperText('npr. "Besplatna dostava iznad 100 KM" ili "Novo: Ljetna kolekcija"'),
                            TextInput::make('banner_url')
                                ->label('Link (opcionalno)')
                                ->helperText('Ostavi prazno ako traka nije klikabilna.'),
                            ColorPicker::make('banner_bg')->label('Pozadinska boja'),
                            ColorPicker::make('banner_fg')->label('Boja teksta'),
                        ]),
                ]),

                Tab::make('Kontakt & mreže')->icon(Heroicon::OutlinedPhone)->schema([
                    Section::make('Kontakt informacije')
                        ->description('Prikazuju se u footeru i na Kontakt stranici.')
                        ->columns(2)
                        ->schema([
                            TextInput::make('contact_email')
                                ->label('E-mail za kontakt')
                                ->email()
                                ->prefixIcon(Heroicon::Envelope),
                            TextInput::make('contact_phone')
                                ->label('Telefon')
                                ->tel()
                                ->prefixIcon(Heroicon::Phone)
                                ->helperText('U prikazu, npr. +387 61 000 000'),
                        ]),

                    Section::make('Poruke — floating dugme')
                        ->description('Ovi brojevi napajaju "chat" dugme u donjem desnom uglu sajta.')
                        ->columns(2)
                        ->schema([
                            TextInput::make('whatsapp_number')
                                ->label('WhatsApp broj')
                                ->helperText('Bez razmaka, sa pozivnim. npr. 38761234567')
                                ->rules(['nullable', 'regex:/^\d{9,15}$/']),
                            TextInput::make('viber_number')
                                ->label('Viber broj')
                                ->helperText('Isti format kao WhatsApp.')
                                ->rules(['nullable', 'regex:/^\d{9,15}$/']),
                        ]),

                    Section::make('Društvene mreže')
                        ->columns(2)
                        ->schema([
                            TextInput::make('instagram_handle')
                                ->label('Instagram korisničko ime')
                                ->prefix('@')
                                ->helperText('Bez @ — samo ime. npr. sinonim_design'),
                            TextInput::make('facebook_url')
                                ->label('Facebook URL')
                                ->url()
                                ->placeholder('https://facebook.com/...'),
                            TextInput::make('tiktok_url')
                                ->label('TikTok URL')
                                ->url()
                                ->placeholder('https://tiktok.com/@...'),
                        ]),
                ]),

                Tab::make('Dostava & plaćanje')->icon(Heroicon::OutlinedTruck)->schema([
                    Section::make('Dostava')
                        ->description('Cijene dostave prikazuju se u korpi i pri narudžbi.')
                        ->columns(2)
                        ->schema([
                            TextInput::make('shipping_flat_rate')
                                ->label('Standardna cijena dostave')
                                ->numeric()
                                ->suffix('KM')
                                ->step(0.5)
                                ->default(5)
                                ->required()
                                ->helperText('Fiksna cijena za sve narudžbe (osim ako je aktivan besplatan prag).'),
                            TextInput::make('shipping_free_over')
                                ->label('Besplatna dostava iznad')
                                ->numeric()
                                ->suffix('KM')
                                ->step(1)
                                ->helperText('Ostavi prazno da isključiš besplatnu dostavu.'),
                            Textarea::make('shipping_note')
                                ->label('Napomena o dostavi')
                                ->rows(3)
                                ->maxLength(300)
                                ->columnSpanFull()
                                ->helperText('Prikazuje se na stranici /dostava. npr. "Dostava BH Pošta, rok 2–5 dana"'),
                        ]),

                    Section::make('Plaćanje')
                        ->description('Trenutno je aktivno samo plaćanje pouzećem. Kartice se mogu dodati kasnije.')
                        ->schema([
                            \Filament\Schemas\Components\Text::make('✓ Plaćanje pouzećem (COD)')->color('success'),
                        ]),
                ]),

            ])->columnSpanFull(),
        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Postavke sačuvane')
            ->body('Promjene su primijenjene na sajt.')
            ->success()
            ->duration(4000)
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Sačuvaj postavke')
                ->submit('save')
                ->icon(Heroicon::Check)
                ->keyBindings(['mod+s']),
        ];
    }
}
