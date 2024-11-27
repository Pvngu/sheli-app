@php
    $desiredOrder = ['campaign_name','reference_number', 'first_name', 'last_name', 'SSN', 'date_of_birth', 'phone_number', 'home_phone', 'email', 'language', 'original_profile_id', 'time_taken', 'started','lead_status', 'assigned_to', 'first_action_by', 'last_action_by'];
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
        @foreach($leads as $lead)
            <tr>
                @if(in_array('campaign_name', $columns))
                    <td>{{ $lead->individual->campaign->name }}</td>
                @endif
                @if(in_array('reference_number', $columns))
                    <td>{{ $lead->individual->reference_number }}</td>
                @endif
                @if(in_array('first_name', $columns))
                    <td>{{ $lead->individual->first_name }}</td>
                @endif
                @if(in_array('last_name', $columns))
                    <td>{{ $lead->individual->last_name }}</td>
                @endif
                @if(in_array('SSN', $columns))
                    <td>{{ $lead->individual->SSN }}</td>
                @endif
                @if(in_array('date_of_birth', $columns))
                    <td>{{ $lead->individual->date_of_birth }}</td>
                @endif
                @if(in_array('phone_number', $columns))
                    <td>{{ $lead->individual->phone_number }}</td>
                @endif
                @if(in_array('home_phone', $columns))
                    <td>{{ $lead->individual->home_phone }}</td>
                @endif
                @if(in_array('email', $columns))
                    <td>{{ $lead->individual->email }}</td>
                @endif
                @if(in_array('language', $columns))
                    <td>{{ $lead->individual->language }}</td>
                @endif
                @if(in_array('original_profile_id', $columns))
                    <td>{{ $lead->individual->original_profile_id }}</td>
                @endif
                @if(in_array('time_taken', $columns))
                    <td>{{ $lead->individual->time_taken }}</td>
                @endif
                @if(in_array('started', $columns))
                    <td>{{ $lead->started }}</td>
                @endif
                @if(in_array('lead_status', $columns))
                    <td>{{ $lead->leadStatus ? $lead->leadStatus->name : null }}</td>
                @endif
                @if(in_array('assigned_to', $columns))
                    <td>{{ $lead->assignedUser ? $lead->assignedUser->name : null }}</td>
                @endif
                @if(in_array('first_action_by', $columns))
                    <td>{{ $lead->individual->firstActioner ? $lead->individual->firstActioner->name : null }}</td>
                @endif
                @if(in_array('last_action_by', $columns))
                    <td>{{ $lead->individual->lastActioner ? $lead->individual->lastActioner->name : null }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>