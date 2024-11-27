@php
    $desiredOrder = ['campaign_name','reference_number', 'first_name', 'last_name', 'SSN', 'date_of_birth', 'phone_number', 'home_phone', 'email', 'language', 'original_profile_id', 'time_taken', 'sale_status', 'assigned_to', 'first_action_by', 'last_action_by'];
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
        @foreach($sales as $sale)
            <tr>
                @if(in_array('campaign_name', $columns))
                    <td>{{ $sale->individual->campaign->name }}</td>
                @endif
                @if(in_array('reference_number', $columns))
                    <td>{{ $sale->individual->reference_number }}</td>
                @endif
                @if(in_array('first_name', $columns))
                    <td>{{ $sale->individual->first_name }}</td>
                @endif
                @if(in_array('last_name', $columns))
                    <td>{{ $sale->individual->last_name }}</td>
                @endif
                @if(in_array('SSN', $columns))
                    <td>{{ $sale->individual->SSN }}</td>
                @endif
                @if(in_array('date_of_birth', $columns))
                    <td>{{ $sale->individual->date_of_birth }}</td>
                @endif
                @if(in_array('phone_number', $columns))
                    <td>{{ $sale->individual->phone_number }}</td>
                @endif
                @if(in_array('home_phone', $columns))
                    <td>{{ $sale->individual->home_phone }}</td>
                @endif
                @if(in_array('email', $columns))
                    <td>{{ $sale->individual->email }}</td>
                @endif
                @if(in_array('language', $columns))
                    <td>{{ $sale->individual->language }}</td>
                @endif
                @if(in_array('original_profile_id', $columns))
                    <td>{{ $sale->individual->original_profile_id }}</td>
                @endif
                @if(in_array('time_taken', $columns))
                    <td>{{ $sale->individual->time_taken }}</td>
                @endif
                @if(in_array('sale_status', $columns))
                    <td>{{ $sale->saleStatus ? $sale->saleStatus->name : null }}</td>
                @endif
                @if(in_array('assigned_to', $columns))
                    <td>{{ $sale->assignedUser ? $sale->assignedUser->name : null }}</td>
                @endif
                @if(in_array('first_action_by', $columns))
                    <td>{{ $sale->individual->firstActioner ? $sale->individual->firstActioner->name : null }}</td>
                @endif
                @if(in_array('last_action_by', $columns))
                    <td>{{ $sale->individual->lastActioner ? $sale->individual->lastActioner->name : null }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>