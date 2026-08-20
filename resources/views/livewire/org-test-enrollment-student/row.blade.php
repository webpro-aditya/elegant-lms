<div>
    <div>

        {{--    <td>
                <label class="primary_checkbox d-flex " for="student{{$row->id}}">
                    <input type="checkbox"
                           id="student{{$row->id}}"
                           data-student="{{$row->id}}"
                           value="{{$row->id}}" name="students[]"
                           class=" singleStudent common-checkbox student{{$row->id}}"
                    >
                    <span class="checkmark"></span>
                </label>

            </td>--}}
        @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('name')))
            <x-livewire-tables::table.td>
                {{$row->name}}
            </x-livewire-tables::table.td>
        @endif

        @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('org_chart_code')))
            <x-livewire-tables::table.td>
                {{$row->branch->fullTextPath}}
            </x-livewire-tables::table.td>
        @endif

        @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('org_position_code')))
            <x-livewire-tables::table.td>
                {{$row->org_position_code}}
            </x-livewire-tables::table.td>
        @endif

        @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('employee_id')))
            <x-livewire-tables::table.td>
                {{$row->employee_id}}
            </x-livewire-tables::table.td>
        @endif
        {{--    <x-livewire-tables::table.td>
                @if($row->status==1)
                    Active
                @else
                    <span class="text-danger">Deactivate</span>
                @endif
            </x-livewire-tables::table.td>--}}

    </div>
</div>
