<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 6;
    
    protected $casts = [
        'password' => 'hashed',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            Section::make(' ')
                ->description(' ')
                ->schema([
                
                FileUpload::make('profile_photo_path')
                ->imageEditor()
                ->imageEditorMode(2)
                ->circleCropper()
                ->getUploadedFileNameForStorageUsing(
                    fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                        ->prepend('profile-photo-'),
                )
                ->label('Photo')
                ->directory('profiles')
                ->visibility('public')
                ->panelLayout('circle')
                ->panelAspectRatio('1:1')
                ->imageCropAspectRatio('1:1')
                ->nullable(),
             ])->columnSpan(1),
             
             Section::make(' ')
             ->description(' ')
             ->schema([
                TextInput::make('name')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->columnSpan(2),
                TextInput::make('password')
                    ->password()
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->columnSpan(2),
                Select::make('role')
                    ->options(User::ROLES)
                    ->required()
                    ->columnSpan(1),
                Select::make('department')
                    ->options([
                    'Sales' => 'Sales',
                    'TSD' => 'TSD',
                    'DBE' => 'DBE',
                    'Admin' => 'Admin',
                    'Accounting' => 'Accounting',
                    'HRAD' => 'HRAD',
                    'Audit' => 'Audit',
                    'Purchasing' => 'Purchasing',
                    'MIS' => 'MIS',
                    'Warehouse' => 'Warehouse',
                    'Others' => 'Others',
                    ])
                    ->columnSpan(1)
                    ->required(),
                ToggleButtons::make('status')->inline()
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'resigned' => 'Resigned',
                    ])
                    ->colors([
                        'active' => 'success',
                        'inactive' => 'info',
                        'resigned' => 'warning',
                    ])
                    ->columnSpan(2)
                    ->nullable(),
             ])->columnSpan(3)
             ->columns(3),
                
                

            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Employees')
            ->defaultPaginationPageOption(25)
            ->deferLoading()
            ->defaultSort('status', 'asc')
            ->recordUrl(null)
            ->columns([
                ImageColumn::make('profile_photo_path')
                    ->circular()
                    ->defaultImageUrl(url('default_profile.jpg'))
                    // ->ring(5)
                    ->size(40)
                    ->label(' '),
                
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('department')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->searchable()
                    ->default('General')
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'info',
                        'resigned' => 'warning',
                        'General' => 'info',
                    }),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->slideOver(),
                Tables\Actions\EditAction::make()
                ->slideOver()
                ->stickyModalFooter(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            // 'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'view' => Pages\ViewUser::route('/{record}'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),

            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
