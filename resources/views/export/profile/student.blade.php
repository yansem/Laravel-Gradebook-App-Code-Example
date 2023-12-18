@if (isset($group['subjects']))
    @foreach($group['subjects'] as $subject)
        <table>
            <thead>
            <tr>
                <th><strong>Предмет:</strong></th>
                <th><strong>{{ $subject['title'] }}</strong></th>
            </tr>
            </thead>
        </table>
        <table>
            <thead>
            <tr>
                <th>Дата</th>
                <th>Занятие</th>
                <th>Оценки</th>
                <th>Посещение</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subject['lessons'] as $lesson)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($lesson['start'])->format('d.m.Y H:i') }}</td>
                    <td>{{ $lesson['title'] }}</td>
                    <td>
                        @if(!empty($lesson['grades']))
                            @foreach($lesson['grades'] as $grade)
                                <div>{{ $grade['grade_value'] }} {{ $grade['work_title'] }}
                                    {{ $grade['comment'] ? '(' . $grade['comment'] . ')' : '' }}</div>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(!empty($lesson['visit']))
                            {{ $lesson['visit']['visited'] ? '+' : 'Н' }}
                            {{ $lesson['visit']['comment'] ? '(' . $lesson['visit']['comment'] . ')' : '' }}
                        @else
                            {{ 'Н' }}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <table>
            <thead>
            <tr>
                <th>Средняя оценка:</th>
                <th>{{ $subject['gradeAvg'] }}</th>
            </tr>
            <tr>
                <th>Посещаемость:</th>
                <th>{{ $subject['visitPercent'] }} %</th>
            </tr>
            </thead>
        </table>
    @endforeach
@else
    <table>
        <thead>
        <tr>
            <th>
                У группы пока нет занятий...
            </th>
        </tr>
        </thead>
    </table>
@endif


