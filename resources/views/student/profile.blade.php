@extends('layouts.registrar')

@push('styles')
    <style>
        .nav-item:hover,
        .yearItem:hover {
            cursor: pointer !important;
        }

    </style>
@endpush

@section('content')
    <div class="row pt-5">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $student->user->fullName }}</h3>
                    <p class="text-muted text-center">{{ $student->course->title }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Student No.</b> <a class="float-right">{{ $student->user->username }}</a>
                        </li>
                        {{-- <li class="list-group-item"> --}}
                        {{-- <b>Subjects This Semester</b> <a class="float-right">999</a> --}}
                        {{-- </li> --}}
                        <li class="list-group-item">
                            <b>Birthdate</b> <a
                                class="float-right">{{ date('F d, Y', strtotime($student->birthdate)) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> <a class="float-right">
                                <v-chip class="bg-success" small>
                                    Active
                                </v-chip>
                            </a>
                        </li>
                    </ul>
                    {{-- <a href="#" class="btn btn-danger btn-block"><b>Suspend</b></a> --}}
                </div>
            </div>

            <ul class="list-group">
                <li class="list-group-item bg-primary">School Year</li>
                @foreach ($schoolYears as $index => $schoolYear)
                    <li class="list-group-item yearItem" id="yearItem-{{ $index }}"
                        onclick="onSelectYear({{ $index }},{{ $student->id }},'yearItem-{{ $index }}')">
                        {{ $schoolYear . ' - ' . (intval($schoolYear) + 1) }}
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills semesterItem" id="navSemester">

                    </ul>
                </div>
            </div>
            <div class="card d-none" id="gradeTable">
                <div class="card-body">
                    <v-simple-table dense>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <td colspan="9"><b>**Note: </b>Red color grades indicates Not Approved by registrar</td>
                                </tr>
                                <tr>
                                    <th class="text-left">
                                        Subject Code
                                    </th>
                                    <th class="text-left">
                                        Subject Name
                                    </th>
                                    <th class="text-left">
                                        Credit
                                    </th>
                                    <th class="text-left">
                                        Instructor
                                    </th>
                                    <th class="text-left">
                                        Grade
                                    </th>
                                    <th class="text-left">
                                        Remarks
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><b id="totalCredit"></b> <br>Total Credits</td>
                                    <td></td>
                                    <td><b id="totalGrade"></b> <br>Grade Average</td>
                                </tr>
                            </tfoot>
                        </template>
                    </v-simple-table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedSemester = 1
        let selectedyear = null
        let selectedStudent = null

        function onSelectYear(year, student, yearID) {
            console.log(year);
            selectedyear = year
            selectedStudent = student
            document.getElementById('totalGrade').innerText = ''
            document.getElementById('gradeTable').classList.add('d-none')
            const tableBody = document.getElementById('tableBody')
            tableBody.innerHTML = ""
            axios.get(`/api/fetchSemesterForYear/${year}/${student}`).then(r => {
                const semesters = r.data.data
                const navSemester = document.getElementById('navSemester')
                document.querySelectorAll('.yearItem').forEach(e => {
                    e.classList.remove('bg-success')
                })
                document.getElementById(yearID).classList.add('bg-success')
                navSemester.innerHTML = ''
                for (const semester in semesters) {
                    navSemester.innerHTML +=
                        `<li class="nav-item ml-1"><a id="semester-${semester}"
                             onclick="fetchGradeForSemester(${semester},${year},'semester-${semester}')" class="nav-link semesterItem" >${semesters[semester]}</a></li>`
                }
            })
        }

        function onSelectSemester(semester) {
            selectedSemester = semester
        }

        function fetchGradeForSemester(semester, year, semesterID) {
            let totalGrade = 0
            let lecTotal = 0
            let labTotal = 0
            let recordsLength = 0
            axios.get(`/api/fetchGradeForSemester/${year}/${semester}/${selectedStudent}`)
                .then(r => {
                    const tableBody = document.getElementById('tableBody')
                    const records = r.data.data
                    recordsLength = records.length
                    tableBody.innerHTML = ""
                    records.map(item => {
                        // const grade = Number.isInteger(item.grade) ? item.grade + ".0" : item.grade
                        tableBody.innerHTML +=
                            `
                            <tr>
                                <td>${item.code}</td>
                                <td>${item.title}</td>
                                <td>${item.units}</td>
                                <td>${item.teacher}</td>
                                <td class="${item.status == 1 ? '' : 'text-danger'}">${item.grade}</td>
                                <td>${item.remarks}</td>
                            </tr>
                        `
                    })
                    document.querySelectorAll('.semesterItem').forEach(e => {
                        e.classList.remove('active')
                    })
                    document.getElementById(semesterID).classList.add('active')
                    if (records.length) {
                        document.getElementById('totalGrade').innerText = r.data.totalGrade
                        document.getElementById('totalCredit').innerText = r.data.totalUnits
                        document.getElementById('gradeTable').classList.remove('d-none')
                    } else {
                        document.getElementById('gradeTable').classList.add('d-none')
                        document.getElementById('totalGrade').innerText = ''
                        document.getElementById('totalCredit').innerText = ''
                    }
                })
        }

        function remarksDeciderV1(grade) {
            if (grade < 75) {
                return "Failed"
            } else {
                return "Passed"
            }
        }

        function gradeDeciderV1(initial_grade) {
            const grade = parseFloat(initial_grade)

            if (grade >= 98) {
                return "1.00"
            } else if (grade >= 95) {
                return "1.25"
            } else if (grade >= 92) {
                return "1.50"
            } else if (grade >= 88) {
                return "1.75"
            } else if (grade >= 85) {
                return "2.00"
            } else if (grade >= 82) {
                return "2.25"
            } else if (grade >= 79) {
                return "2.50"
            } else if (grade >= 76) {
                return "2.75"
            } else if (grade >= 74.5) {
                return "3.00"
            } else if (grade == 0) {
                return "INC"
            } else if (grade == 4) {
                return "DRP"
            } else {
                /*5.00*/
                return "5.00"
            }
        }

        /*Todo remarks */
        function remarksDecider(g) {
            const grade = parseFloat(g);
            if (grade == 4) {
                return "DRP";
            } else if (grade == 0) {
                return "INC";
            } else if (grade == 5) {
                return "Failed";
            } else {
                return "Passed";
            }
        }

        function gradeDecider(initial_grade) {
            const grade = parseFloat(initial_grade)
            if (grade == 4) {
                return "DRP";
            } else if (grade == 0) {
                return "INC";
            } else if (grade === 5) {
                return "5.00";
            } else {
                return grade;
            }
        }
    </script>
@endpush
