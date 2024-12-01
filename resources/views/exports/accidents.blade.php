@php
    $desiredOrder = ['date_of_accident', 'injured_person', 'reporting_user', 'area', 'days_absent', 'description', 'status', 'action'];
    usort($columns, function($a, $b) use ($desiredOrder) {
        $posA = array_search($a, $desiredOrder);
        $posB = array_search($b, $desiredOrder);
        return $posA - $posB;
    });
@endphp
<table>
    <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ str_replace('_', ' ', $column) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($accidents as $accident)
            <tr>
            @if(in_array('date_of_accident', $columns))
                <td>{{ $accident->date_of_accident }}</td>
            @endif
            @if(in_array('injured_person', $columns))
                <td>
                    @if($accident->injuredPerson)
                        {{ $accident->injuredPerson->name }}
                    @endif
                </td>
            @endif
            @if(in_array('reporting_user', $columns))
                <td>
                    @if($accident->reportingUser)
                        {{ $accident->reportingUser->name }}
                    @endif
                </td>
            @endif
            @if(in_array('area', $columns))
                <td>
                    @if($accident->area)
                        {{ $accident->area->name }}
                    @endif
                </td>
            @endif
            @if(in_array('days_absent', $columns))
                <td>{{ $accident->days_absent }}</td>
            @endif
            @if(in_array('description', $columns))
                <td>{{ $accident->description }}</td>
            @endif
            @if(in_array('status', $columns))
                <td>{{ $accident->status }}</td>
            @endif
            </tr>
        @endforeach
    </tbody>
</table>
