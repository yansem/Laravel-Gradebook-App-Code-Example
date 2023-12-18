@foreach($subject['groups'] as $group)
    <table>
        <thead>
        <tr>
            <th><strong>Группа:</strong></th>
            <th><strong>{{ $group['title'] }}</strong></th>
        </tr>
        </thead>
    </table>
    <table>
        <thead>
        <tr>
            <th>Дата</th>
            <th>Занятие</th>
            <th>Студент</th>
            <th>Оценки</th>
            <th>Посещение</th>
        </tr>
        </thead>
        <tbody>
        @foreach($group['lessons'] as $lesson)
            @if (!empty($lesson['students']))
                @foreach($lesson['students'] as $student)
                    <tr>
                        <td>{{ $lesson['start'] }}</td>
                        <td>{{ $lesson['title'] }}</td>
                        <td>{{ implode(' ', [$student['last_name'], $student['first_name'], $student['patronymic']]) }}</td>
                        <td>
                            @if(!empty($student['grades']))
                                @foreach($student['grades'] as $grade)
                                    <div>{{ $grade['grade_value'] }} {{ $grade['work_title'] }}
                                        {{ $grade['comment'] ? '(' . $grade['comment'] . ')' : '' }}</div>
                                @endforeach
                            @else
                                -
                            @endif

                        </td>
                        <td>
                            @if(!empty($student['visit']))
                                {{ $student['visit']['visited'] ? '+' : 'Н' }}
                                {{ $student['visit']['comment'] ? '(' . $student['visit']['comment'] . ')' : '' }}
                            @else
                                {{ 'Н' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $lesson['start'] }}</td>
                    <td>{{ $lesson['title'] }}</td>
                    <td>У группы пока нет студентов...</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>Средняя оценка:</th>
            <th>{{ $group['gradeAvg'] }}</th>
        </tr>
        <tr>
            <th>Посещаемость:</th>
            <th>{{ $group['visitPercent'] }} %</th>
        </tr>
        </thead>
    </table>
@endforeach
