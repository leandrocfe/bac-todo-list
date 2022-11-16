<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationGroup = 'Tasks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Card::make()->schema([

                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('task_group_id')
                        ->relationship('taskGroup', 'title')
                        ->required(),

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\RichEditor::make('description')
                        ->maxLength(65535)
                        ->columnSpan(2),
                ])
                ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('taskGroup.title')
                    ->sortable()
                    ->searchable()
                    ->colors([
                        'secondary',
                        'primary' => 'Backlog',
                        'warning' => 'In Progress',
                        'success' => 'Done',
                        'danger' => 'To Do',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/y H:i'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/y H:i'),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->searchable()
                    ->relationship('user', 'name'),

                SelectFilter::make('taskGroup')
                    ->searchable()
                    ->relationship('taskGroup', 'title')
                    ->multiple()
                    ->label('Grupo da tarefa'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
