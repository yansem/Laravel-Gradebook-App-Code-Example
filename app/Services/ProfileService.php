<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Support\Str;


class ProfileService
{
    public function createArrayForTeacher(): array
    {
        $gradesAll = Grade::withTrashed()->get()->pluck('value', 'id');
        $worksAll = Work::withTrashed()->get()->pluck('title', 'id');
        $subjects = [];
        $lessons = auth()->user()->lessons()->with('groups.students', 'subject', 'students_grades', 'students_visits')->get()->toArray();
        foreach ($lessons as $lesson) {
            $subjectId = $lesson['subject']['id'];
            if (!isset($subjects[$subjectId])) {
                $subjects[$subjectId] = $lesson['subject'];
            }
        }
        foreach ($lessons as $lesson) {
            $subjectId = $lesson['subject']['id'];
            foreach ($lesson['groups'] as $group) {
                if (!isset($subjects[$subjectId]['groups'][$group['id']])) {
                    $subjects[$subjectId]['groups'][$group['id']] = $group;
                    $subjects[$subjectId]['groups'][$group['id']]['visitTotal'] = 0;
                    $subjects[$subjectId]['groups'][$group['id']]['gradeListTotal'] = [];
                }

                $subjectGroup = $subjects[$subjectId]['groups'][$group['id']];
                $subjectGroupLesson = $lesson;
                unset($subjectGroupLesson['groups']);
                unset($subjectGroupLesson['subject']);
                $subjectGroupLesson['students'] = collect($group['students'])
                    ->keyBy('id')->toArray();
                if (!empty($subjectGroupLesson['students'])) {
                    foreach ($subjectGroupLesson['students'] as &$student) {
                        $student['grades'] = [];
                        $student['visit'] = [];
                    }

                    if (!empty($subjectGroupLesson['students_grades'])) {
                        $subjectGroupLesson['students_grades'] = collect($lesson['students_grades'])
                            ->filter(function ($value, $key) use ($group) {
                                return $value['pivot']['group_id'] === $group['id'];
                            })->toArray();
                        foreach ($subjectGroupLesson['students_grades'] as &$student_grade) {
                            $student_grade['pivot']['work_title'] = $worksAll[$student_grade['pivot']['work_id']];
                            $student_grade['pivot']['grade_value'] = $gradesAll[$student_grade['pivot']['grade_id']];
                            if (isset($subjectGroupLesson['students'][$student_grade['id']])) {
                                $subjectGroupLesson['students'][$student_grade['id']]['grades'][] = $student_grade['pivot'];
                            }
                        }
                    }
                    unset($subjectGroupLesson['students_grades']);

                    if (!empty($lesson['students_visits'])) {
                        $subjectGroupLesson['students_visits'] = collect($lesson['students_visits'])
                            ->filter(function ($value, $key) use ($group) {
                                return $value['pivot']['group_id'] === $group['id'];
                            })->toArray();
                        foreach ($subjectGroupLesson['students_visits'] as $student_visit) {
                            if (isset($subjectGroupLesson['students'][$student_visit['id']])) {
                                $subjectGroupLesson['students'][$student_visit['id']]['visit'] = $student_visit['pivot'];
                            }
                        }
                    }
                    unset($subjectGroupLesson['students_visits']);

                    foreach ($subjectGroupLesson['students'] as &$student) {
                        $gradeList = [];
                        if (!empty($student['grades'])) {
                            foreach ($student['grades'] as $grade) {
                                $gradeValue = $gradesAll[$grade['grade_id']];
                                $gradeList[] = $gradeValue;
                            }
                            $subjectGroup['gradeListTotal'] = array_merge($subjectGroup['gradeListTotal'], $gradeList);
                        }
                        if (!empty($student['visit'])) {
                            $subjectGroup['visitTotal'] = $student['visit']['visited']
                                ? $subjectGroup['visitTotal'] + 1
                                : $subjectGroup['visitTotal'];
                        }
                    }
                }

                unset($subjectGroup['students']);

                $subjectGroup['lessons'][$lesson['id']] = $subjectGroupLesson;
                $subjects[$subjectId]['groups'][$group['id']] = $subjectGroup;
            }
        }
        foreach ($subjects as &$subject) {
            if (isset($subject['groups'])) {
                foreach ($subject['groups'] as &$group) {
                    $group['gradeAvg'] = (count($group['gradeListTotal']))
                        ? round((array_sum($group['gradeListTotal']) / count($group['gradeListTotal'])), 1)
                        : '-';
                    $groupStudentCount = 0;
                    foreach ($group['lessons'] as $lesson) {
                        $groupStudentCount += count($lesson['students']);
                    }
                    $group['visitPercent'] = ($group['visitTotal'])
                        ? round($group['visitTotal'] / $groupStudentCount * 100)
                        : '0';
                }
            }
        }

        $subjects = collect($subjects)->sortBy(function ($subject) {
            return Str::lower($subject['title']);
        }, SORT_NATURAL)->toArray();

        foreach ($subjects as &$subject) {
            if (isset($subject['groups'])) {
                $subject['groups'] = collect($subject['groups'])->sortBy(function ($subject) {
                    return Str::lower($subject['title']);
                }, SORT_NATURAL)->toArray();
                foreach ($subject['groups'] as &$group) {
                    $group['lessons'] = collect($group['lessons'])->sortBy([['start', 'asc'], ['title', 'asc']])->toArray();
                    foreach ($group['lessons'] as &$lesson) {
                        $lesson['start'] = Carbon::parse($lesson['start'])->format('d.m.Y H:i');
                        $lesson['students'] = collect($lesson['students'])->sortBy(function ($student) {
                            return Str::lower($student['last_name']);
                        }, SORT_NATURAL)->toArray();
                    }
                }
            }
        }

        return $subjects;
    }

    public function createArrayForStudent(): array
    {
        $gradesAll = Grade::withTrashed()->get()->pluck('value', 'id');
        $worksAll = Work::withTrashed()->get()->pluck('title', 'id');
        $userId = auth()->user()->id;
        $groups = auth()->user()->groups()
            ->with(['lessons.students_grades' => function ($q1) use ($userId) {
                $q1->whereUserId($userId);
            }, 'lessons.students_visits' => function ($q1) use ($userId) {
                $q1->whereUserId($userId);
            }, 'lessons.subject'])
            ->get()
            ->sortBy('title', SORT_NATURAL)
            ->toArray();

        foreach ($groups as &$group) {
            if (!empty($group['lessons'])) {
                $group['lessons'] = collect($group['lessons'])->sortBy('start')->toArray();
                foreach ($group['lessons'] as $lesson) {
                    if (!isset($group['subjects'][$lesson['subject']['id']])) {
                        $group['subjects'][$lesson['subject']['id']] = $lesson['subject'];
                    }
                    $group['subjects'][$lesson['subject']['id']]['lessons'][] = $lesson;
                }
                $group['subjects'] = collect($group['subjects'])->sortBy(function ($subject) {
                    return Str::lower($subject['title']);
                }, SORT_NATURAL)->toArray();
            }
        }
        foreach ($groups as &$group) {
            if (isset($group['subjects'])) {
                foreach ($group['subjects'] as &$subject) {
                    $visitTotal = 0;
                    $gradeListTotal = [];
                    foreach ($subject['lessons'] as &$lesson) {
                        $lesson['start'] = Carbon::parse($lesson['start'])->format('d.m.Y H:i');
                        $gradeList = [];
                        $lesson['grades'] = [];
                        $lesson['visit'] = [];
                        foreach ($lesson['students_grades'] as &$student_grade) {
                            if (isset($student_grade['pivot']['grade_id'])) {
                                $student_grade['pivot']['grade_value'] = $gradesAll[$student_grade['pivot']['grade_id']];
                                $student_grade['pivot']['work_title'] = $worksAll[$student_grade['pivot']['work_id']];
                                $lesson['grades'][] = $student_grade['pivot'];
                                $gradeList[] = $student_grade['pivot']['grade_value'];
                            }
                        }
                        unset($lesson['students_grades']);
                        if (!empty($lesson['students_visits'])) {
                            $lesson['visit'] = $lesson['students_visits'][0]['pivot'];
                            $visitTotal = $lesson['visit']['visited'] ? $visitTotal + 1 : $visitTotal;
                        }
                        unset($lesson['students_visits']);
                        $gradeListTotal = array_merge($gradeListTotal, $gradeList);
                        unset($lesson['subject']);
                    }
                    $subject['gradeAvg'] = count($gradeListTotal)
                        ? round((array_sum($gradeListTotal) / count($gradeListTotal)), 1)
                        : '-';
                    $subject['visitPercent'] = $visitTotal ? round(($visitTotal / count($subject['lessons']) * 100)) : '0';
                }
            }
            unset($group['lessons']);
        }
        return $groups;
    }
}
