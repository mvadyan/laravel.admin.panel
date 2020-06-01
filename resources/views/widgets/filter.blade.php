<div class="nav-tabs-custom" id="filter">
    <ul class="nav nav-tabs">
        @php $i = 1; @endphp
        @foreach($groups as $group_id => $group_item)
            <li class="{{$i==1 ? 'active' : ''}}">
                <a href="#tab_{{$group_id}}" data-toggle="tab" aria-expanded="true">
                    {{$group_item}}
                </a>
            </li>
            @php $i++; @endphp
        @endforeach

        <li class="pull-right">
            <a href="#" id="reset-filter">Сброс</a>
        </li>
    </ul>

    <div class="tab-content">
        @if(!empty($attrs))
            @php $i = 1; @endphp

            @foreach($groups as $group_id => $group_item)
                <div class="tab-pane {{$i==1 ? 'active' : ''}}" id="tab_{{$group_id}}">
                    @foreach($attrs[$group_id] as $attr_id => $value)

                        @if(!empty($filter) && in_array($attr_id, $filter))
                            @php $checked = ' checked'; @endphp
                        @else
                            @php $checked = null; @endphp
                        @endif

                        <div class="form-group">
                            <label>
                                <input type="radio" name="attrs[{{$group_id}}]"
                                       value="{{$attr_id}}" {{$checked}}>
                                {{$value}}
                            </label>
                        </div>
                        @php $i++; @endphp
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

</div>
