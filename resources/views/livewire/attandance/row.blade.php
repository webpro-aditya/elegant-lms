<div>
    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('sl')))
        <x-livewire-tables::table.td>
            {{ ++$this->serial }}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('user.name')))
        <x-livewire-tables::table.td>
            {{$row->user->name}}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('user.org_chart_code')))
        <x-livewire-tables::table.td>
            {{$row->user->branch->fullTextPath}}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('user.org_position_code')))
        <x-livewire-tables::table.td>
            {{$row->user->position->name}}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('user.employee_id')))
        <x-livewire-tables::table.td>
            {{$row->user->employee_id}}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('created_at')))
        <x-livewire-tables::table.td>
            {{showDate($row->created_at)}}
        </x-livewire-tables::table.td>
    @endif
    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('class_type')))
        <x-livewire-tables::table.td>
            {{$row->class_type==3?trans('org.Virtual Class'):trans('org.Offline Class')}}
        </x-livewire-tables::table.td>
    @endif
    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('course')))
        <x-livewire-tables::table.td>
            {{$row->course->title}}
        </x-livewire-tables::table.td>
    @endif
    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('class')))
        <x-livewire-tables::table.td>
            {{$row->class->title}}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('attend')))
        <x-livewire-tables::table.td>
            {{$row->attend}}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('total_score')))
        <x-livewire-tables::table.td>
            {{$row->total_score}}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('pass_rate')))
        <x-livewire-tables::table.td>
            {{$row->pass_rate}}
        </x-livewire-tables::table.td>
    @endif
    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('actual_score')))
        <x-livewire-tables::table.td>
            {{$row->actual_score}}
        </x-livewire-tables::table.td>
    @endif

    @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('status')))
        <x-livewire-tables::table.td>
            @if(!empty($row->total_score) && !empty($row->pass_rate) && !empty($row->actual_score))
                @php
                    $actual_rate =getPercentage($row->actual_score,$row->total_score);
                    if ($actual_rate>=$row->pass_rate){
                        echo trans('org.Pass');
                    }else{
                        echo trans('org.Fail');
                    }
                @endphp
            @else
                {{__('org.Fail')}}
            @endif
        </x-livewire-tables::table.td>
    @endif


</div>
