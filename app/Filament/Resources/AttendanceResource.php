<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->disabled(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->disabled(),
                Forms\Components\DateTimePicker::make('clock_in')
                    ->required(),
                Forms\Components\DateTimePicker::make('clock_out'),
                Forms\Components\Select::make('status')
                    ->options([
                        'present' => 'Present',
                        'late' => 'Late',
                        'permit' => 'Permit',
                        'sick' => 'Sick',
                        'alpha' => 'Alpha',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_revision')
                    ->label('Is Revision?')
                    ->disabled(),
                Forms\Components\Textarea::make('note')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clock_in')
                    ->dateTime('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('clock_out')
                    ->dateTime('H:i')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'present',
                        'warning' => 'late',
                        'info' => 'permit',
                        'danger' => 'sick',
                        'secondary' => 'alpha',
                    ]),
                Tables\Columns\IconColumn::make('is_revision')
                    ->boolean()
                    ->label('Revised'),
                Tables\Columns\TextColumn::make('note')
                    ->limit(30)
                    ->toggleable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => 'Present',
                        'late' => 'Late',
                        'permit' => 'Permit',
                        'sick' => 'Sick',
                        'alpha' => 'Alpha',
                    ]),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
                Filter::make('monthly_report')
                    ->label('Monthly Report (24th-23rd)')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->options([
                                1 => 'January',
                                2 => 'February',
                                3 => 'March',
                                4 => 'April',
                                5 => 'May',
                                6 => 'June',
                                7 => 'July',
                                8 => 'August',
                                9 => 'September',
                                10 => 'October',
                                11 => 'November',
                                12 => 'December',
                            ])
                            ->default(now()->month)
                            ->required(),
                        Forms\Components\Select::make('year')
                            ->options(function () {
                                $years = [];
                                $currentYear = now()->year;
                                for ($i = $currentYear - 2; $i <= $currentYear + 1; $i++) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            })
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['month']) || !isset($data['year'])) {
                            return $query;
                        }

                        $month = $data['month'];
                        $year = $data['year'];

                        // Calculate period from 24th of previous month to 23rd of current month
                        $prevMonth = $month == 1 ? 12 : $month - 1;
                        $prevYear = $month == 1 ? $year - 1 : $year;

                        $startDate = Carbon::create($prevYear, $prevMonth, 24);
                        $endDate = Carbon::create($year, $month, 23);

                        return $query
                            ->whereDate('date', '>=', $startDate)
                            ->whereDate('date', '<=', $endDate);
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->label('Export to Excel'),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
