@php
    $desiredOrder = ['name', 'role', 'phone', 'email', 'address', 'status', 'time_taken', 'count_created_sales', 'count_assigned_sales', 'count_created_leads', 'count_last_action_by', 'count_first_action_by', 'count_lead_follow_up', 'count_sent_sms_messages'];
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
        @foreach($users as $user)
            <tr>
                @if(in_array('name', $columns))
                    <td>{{ $user->name }}</td>
                @endif
                @if(in_array('role', $columns))
                    <td>{{ $user->role ? $user->role->name : null }}</td>
                @endif
                @if(in_array('phone', $columns))
                    <td>{{ $user->phone }}</td>
                @endif
                @if(in_array('email', $columns))
                    <td>{{ $user->email }}</td>
                @endif
                @if(in_array('address', $columns))
                    <td>{{ $user->address }}</td>
                @endif
                @if(in_array('status', $columns))
                    <td>{{ $user->status }}</td>
                @endif
                @if(in_array('time_taken', $columns))
                    <td>{{ $user->getTimeTaken($startDate, $endDate) }}</td>
                @endif
                @if(in_array('count_created_sales', $columns))
                    <td>{{ $user->getCreatedSalesCount($startDate, $endDate) }}</td>
                @endif
                @if(in_array('count_assigned_sales', $columns))
                    <td>{{ $user->getAssignedSalesCount($startDate, $endDate) }}</td>
                @endif
                @if(in_array('count_created_leads', $columns))
                    <td>{{ $user->getCreatedIndividualCount($startDate, $endDate) }}</td>
                @endif
                @if(in_array('count_last_action_by', $columns))
                    <td>{{ $user->getLastActionedIndividualCount($startDate, $endDate) }}</td>
                @endif
                @if(in_array('count_first_action_by', $columns))
                    <td>{{ $user->getFirstActionedIndividualCount($startDate, $endDate) }}</td>
                @endif
                @if(in_array('count_lead_follow_up', $columns))
                    <td>{{ $user->getFollowUpsCount($startDate, $endDate) }}</td>
                @endif
                @if(in_array('count_sent_sms_messages', $columns))
                    <td>{{ $user->getSentSmsCount($startDate, $endDate) }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
