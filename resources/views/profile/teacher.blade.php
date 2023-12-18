@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row mb-2">
                    <div class="col-9">
                        <h2>Мой профиль</h2>
                    </div>
                    <div class="col-3 text-end">
                        <a href="{{ route('profile.export') }}" class="text-decoration-none me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-download" viewBox="0 0 16 16">
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
                                <path
                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"></path>
                            </svg>
                            Скачать xlsx
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">ФИО</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ auth()->user()->full_name }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Логин</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ auth()->user()->login }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Роль</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ auth()->user()->role->title }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                            @foreach($subjects as $subject)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                            id="tab_subject_{{ $subject['id'] }}" data-bs-toggle="tab"
                                            data-bs-target="#subject_{{ $subject['id'] }}" type="button" role="tab"
                                            aria-controls="subject_{{ $subject['id'] }}" aria-selected="true">
                                        {{ $subject['title'] }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            @foreach($subjects as $subject)
                                <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}"
                                     id="subject_{{ $subject['id'] }}" role="tabpanel"
                                     aria-labelledby="subject_{{ $subject['id'] }}">
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    @foreach($subject['groups'] as $group)
                                                        <h2 class="h3 mt-3">{{ $group['title'] }}</h2>
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table table-profile table-hover table-sm componentTable bg-white">
                                                                <thead>
                                                                <tr>
                                                                    <th class="col w-20">Дата</th>
                                                                    <th class="col w-20">Занятие</th>
                                                                    <th class="col w-20">Студент</th>
                                                                    <th class="col w-20">Оценки</th>
                                                                    <th class="col w-20">Посещение</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($group['lessons'] as $lesson)
                                                                    @foreach($lesson['students'] as $student)
                                                                        <tr>
                                                                            <td class="col w-20">{{ \Carbon\Carbon::parse($lesson['start'])->format('d.m.Y H:i') }}</td>
                                                                            <td class="col w-20">{{ $lesson['title'] }}</td>
                                                                            <td class="col w-20">{{ implode(' ', [$student['last_name'], $student['first_name'], $student['patronymic']]) }}</td>
                                                                            <td class="col w-20">
                                                                                @if(!empty($student['grades']))
                                                                                    @foreach($student['grades'] as $grade)
                                                                                        <div>{{ $grade['grade_value'] }} {{ $grade['work_title'] }}
                                                                                            {{ $grade['comment'] ? '(' . $grade['comment'] . ')' : '' }}</div>
                                                                                    @endforeach
                                                                                @else
                                                                                    -
                                                                                @endif

                                                                            </td>
                                                                            <td class="col w-20">
                                                                                @if(!empty($student['visit']))
                                                                                    {{ $student['visit']['visited'] ? '+' : 'Н' }}
                                                                                    {{ $student['visit']['comment'] ? '(' . $student['visit']['comment'] . ')' : '' }}
                                                                                @else
                                                                                    {{ 'Н' }}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                            <div>Средняя оценка: {{ $group['gradeAvg'] }}</div>
                                                            <div>Посещаемость: {{ $group['visitPercent'] }} %</div>
                                                            <hr/>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
