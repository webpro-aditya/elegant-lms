<div>


    <x-livewire-tables::table.td>
        {{ ++$index*request()->input('page',1)  }}
    </x-livewire-tables::table.td>
    <x-livewire-tables::table.td>
        {{$row->title}}
    </x-livewire-tables::table.td>

    <x-livewire-tables::table.td>
        {{$row->category}}
    </x-livewire-tables::table.td>

    <x-livewire-tables::table.td>
        {{$row->type}}
    </x-livewire-tables::table.td>


    <x-livewire-tables::table.td>
        {{$row->user->name}}
    </x-livewire-tables::table.td>

    <x-livewire-tables::table.td>
        <label class="switch_toggle">
            <input type="checkbox" class="status_enable_disable"
                   @if (@$row->status == 1) checked
                   @endif value="{{@$row->id }}">
            <i class="slider round"></i>
        </label>
    </x-livewire-tables::table.td>

    <x-livewire-tables::table.td>
        {{showDate($row->created_at)}}
    </x-livewire-tables::table.td>

    <x-livewire-tables::table.td>
        <div class="dropdown CRM_dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button"
                    id="dropdownMenu2" data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                {{__('common.Action')}}
            </button>
            <div class="dropdown-menu dropdown-menu-right"
                 aria-labelledby="dropdownMenu2">

                <a class="dropdown-item" href="{{asset($row->link)}}">{{__('common.View')}}</a>
                @if (permissionCheck('org.material.update'))
                    <button data-item="{{$row}}"
                            class="editMaterial dropdown-item"
                            type="button">{{__('common.Edit')}}</button>
                @endif
                @if (permissionCheck('org.material.delete'))
                    <button data-id="{{$row->id}}"
                            class="deleteMaterial dropdown-item"
                            type="button">{{__('common.Delete')}}</button>
                @endif


            </div>
        </div>

    </x-livewire-tables::table.td>


</div>
