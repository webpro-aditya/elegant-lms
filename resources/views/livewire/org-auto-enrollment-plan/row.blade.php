<div>
    <div>
        <div>

            @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('title')))
                <x-livewire-tables::table.td>
                    {{$row->title}}
                </x-livewire-tables::table.td>
            @endif



            @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('days')))

                <x-livewire-tables::table.td>
                    {{$row->days!=0?$row->days.' Days':''}}
                </x-livewire-tables::table.td>
            @endif

            @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('created_by')))

                <x-livewire-tables::table.td>
                    {{$row->createdBy->name}}
                </x-livewire-tables::table.td>
            @endif

            @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('created_at')))

                <x-livewire-tables::table.td>
                    {{showDate($row->created_at)}}
                </x-livewire-tables::table.td>
            @endif

        </div>

    </div>

</div>
