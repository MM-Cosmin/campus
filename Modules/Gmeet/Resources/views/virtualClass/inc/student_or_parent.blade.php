<div class="col-lg-12 student-details up_admin_visitor">
    <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">

        @foreach ($records as $key => $record)
            <li class="nav-item">
                <a class="nav-link @if ($key == 0) active @endif " href="#tab{{ $key }}" role="tab"
                    data-toggle="tab">
                    @if(moduleStatusCheck('University'))
                    {{$record->semesterLabel->name}} ({{$record->unSection->section_name}}) - {{@$record->unAcademic->name}}
                        @else 
                        {{$record->class->class_name}} ({{$record->section->section_name}}) 
                    @endif 
                 </a>
            </li>
        @endforeach

    </ul>

    @php
        if(moduleStatusCheck('University')){
            $meetings = [];
        }else{
            $meetings = in_array(auth()->user()->role_id, [2,3]) ? $record->StudentGmeetVirtualClass  : $meetings;
        }
           
        @endphp


    <!-- Tab panes -->
    <div class="tab-content mt-40">
        <!-- Start Fees Tab -->
        @foreach ($records as $key => $record)
            <div role="tabpanel" class="tab-pane fade  @if ($key == 0) active show @endif"
                id="tab{{ $key }}">
                @php
                    $meetings = $record->StudentGmeetVirtualClass;
                @endphp 
                 @include('gmeet::virtualClass.inc.list', ['meetings' => $meetings])
            </div>


        @endforeach
    </div>
</div>