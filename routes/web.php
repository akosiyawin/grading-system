<?php

use App\Http\Controllers\AuthorizeController;
use App\Http\Controllers\PageHandlerController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/handler');

/* Redirector */
Route::redirect('/teacher', '/teacher-subject');
//Route::redirect('/registrar', '/registrar-subject');


Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/handler', [PageHandlerController::class, 'handle'])->name('handler');

//Registrar Routes
Route::get('/registrar-subject', [RegistrarController::class, 'createSubject'])->name('registrar.subject');
Route::get('/registrar', [RegistrarController::class, 'index'])->name('registrar.index');
Route::get('/registrar-year-semester', [RegistrarController::class, 'yearSemesterView'])->name('registrar.yearSemesterView');
Route::get('/registrar-student', [RegistrarController::class, 'studentView'])->name('registrar.studentView');
Route::get('/registrar-teacher', [RegistrarController::class, 'teacherView'])->name('registrar.teacherView');
Route::get('/registrar-courses', [RegistrarController::class, 'setup'])->name('registrar.setup');
Route::get('/registrar-announcement', [RegistrarController::class, 'announcementIndex'])->name('registrar.announcement');

//Teacher Routes
Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher.index');
Route::get('/teacher-subject', [TeacherController::class, 'createSubject'])->name('teacher.subject');
Route::get('/teacher-assign-student-to-subject', [TeacherController::class, 'assignStudentsToSubjects'])->name('teacher.assignStudentsToSubjects');

//Student Routes
//Route::get('/student', [StudentController::class, 'index'])->name('student.index');
Route::get('/announcement', [StudentController::class, 'announcementIndex'])->name('student.announcement');

Route::get('/studentProfile/{student}', [RegistrarController::class, 'studentProfile'])->name('student.profile');
Route::get('/changePassword', [AuthorizeController::class, 'changePassword'])->name('changePassword');

Route::view('/technical-assistance-only', 'assistance');

/* Group Routes */
Route::prefix('api')->group(function () {
    Route::post("/resetPassword/{user}", [AuthorizeController::class, "resetPassword"]);
    Route::get('/technical-assistance-only/students', [\App\Http\Controllers\Controller::class, 'assistance']);
    //Course
    Route::get('/courses', [RegistrarController::class, 'courseIndex']);
    Route::post('/courses', [RegistrarController::class, 'courseStore']);
    Route::delete('/courses/{course}', [RegistrarController::class, 'destroyCourse']);
    //Department
    Route::post('/departments', [RegistrarController::class, 'departmentStore']);
    Route::get('/departments', [RegistrarController::class, 'departmentIndex']);
    Route::delete('/departments/{department}', [RegistrarController::class, 'destroyDepartment']);
    //Department - Subjects
    Route::get('/department-subjects', [TeacherController::class, 'departmentSubjectIndex']);
    //Periods
    Route::get('/periods', [AuthorizeController::class, 'periodsIndex']);
    //Roles
    Route::get('/roles', [AuthorizeController::class, 'rolesIndex']);
    //Teacher
    Route::post('/teachers', [RegistrarController::class, 'teacherStore']);
    Route::patch('/updateSubjectStatus/{subject}', [TeacherController::class, 'updateSubjectStatus']);
    Route::get('/teachers', [RegistrarController::class, 'teacherIndex']);
    Route::get('/teachers/{teacher}', [RegistrarController::class, 'teacherInfo']);
    Route::get('/mySubjects', [TeacherController::class, 'mySubjects']);
    Route::post('/storeStudentSubject', [TeacherController::class, 'storeStudentSubject']);
    Route::delete('/destroyStudentSubject/{subject}', [TeacherController::class, 'destroyStudentSubject']);
    Route::get('/studentWithoutSubject/{subject}', [TeacherController::class, 'studentWithoutSubject']);
    Route::get('/studentOfMySubject/{subject}', [TeacherController::class, 'studentOfMySubject']);
    Route::get('/studentGrade/{student}/{subject}', [TeacherController::class, 'studentGrade']);
    Route::patch('/updateGrade/{student}/{subject}', [TeacherController::class, 'updateGrade']);
    Route::patch('/updateGrades', [TeacherController::class, 'updateGrades']);
    Route::patch('/resubmission/{subject}/{resubmission}', [RegistrarController::class, 'approveResubmission']);
    Route::put('/resubmission/{subject}', [RegistrarController::class, 'approveAllResubmission']);

    Route::get('/fetchSemesterForYear/{schoolYear}/{student}', [RegistrarController::class, 'fetchSemesterForYear']);
    Route::get('/fetchGradeForSemester/{schoolYear}/{semester}/{student}', [RegistrarController::class, 'fetchGradeForSemester']);

    Route::delete('/teachers/{user}', [RegistrarController::class, 'destroyTeacher']);
    Route::get('/teachers-assigned/{subject}', [RegistrarController::class, 'teacherAssigned']);
    // Subject
    Route::post('/subject', [RegistrarController::class, 'subjectStore']);
    // Year
    Route::post('/year', [RegistrarController::class, 'yearStore']);
    Route::get('/year', [RegistrarController::class, 'yearIndex']);
    // Semester
    Route::patch('/semester/{semester}', [RegistrarController::class, 'semesterUpdate']);
    Route::get('/semester-active', [RegistrarController::class, 'activeSemester']);
    // Subject
    Route::get('/subjects', [RegistrarController::class, 'subjectIndex']);
    Route::get('/subjects-designated-to-teacher', [RegistrarController::class, 'subjectDesignated']);
    Route::patch('/subjects-designate/{teacher}/{subject}', [RegistrarController::class, 'designateSubject']);
    Route::delete('/subjects-revoke/{teacher}/{subject}', [RegistrarController::class, 'revokeSubject']);
    // User
    Route::patch('/user-status/{user}', [RegistrarController::class, 'updateUserStatus']);
    // Student
    Route::get('/students', [RegistrarController::class, 'studentIndex']);
    Route::post('/students', [RegistrarController::class, 'storeStudent']);
    Route::post('/students-bulk', [RegistrarController::class, 'bulkStudents']);
    Route::post('/subjects-bulk', [RegistrarController::class, 'bulkSubjects']);
    Route::delete('/students/{user}', [RegistrarController::class, 'destroyStudent']);
    Route::patch('/approveGrade/{subject}', [RegistrarController::class, 'approveGrade']);
    Route::get('/teacherSubjects/{teacher}', [RegistrarController::class, 'teacherSubjects']);
    Route::get('/subjectStudentsGrade/{subject}', [RegistrarController::class, 'subjectStudentsGrade']);
    Route::patch('/approveAllGrade/{subject}', [RegistrarController::class, 'approveAllGrade']);
    Route::post('/announcement', [RegistrarController::class, 'announcementStore']);
    Route::get('/announcement', [AuthorizeController::class, 'announcement']);
    /*DIto na ko, Iba yung pinupuntahan ng announcenment api*/
    Route::patch('/updatePassword', [AuthorizeController::class, 'updatePassword']);

    //teachers
    Route::post('/subjects/acquired', [TeacherController::class, 'Acquired_subjects']);
    Route::post('/view/subjects/cs/{user_id}', [TeacherController::class, 'View_subject_cs_department']);
    Route::post('/view/subjects/coe', [TeacherController::class, 'View_subject_coe_department']);

    Route::post('/subjects/acquired', [TeacherController::class, 'Acquired_subjects']);
    Route::post('/view/subjects/cs', [TeacherController::class, 'View_subject_cs_department']);
    Route::post('/view/subjects/coe', [TeacherController::class, 'View_subject_coe_department']);

    //students

    Route::post('/student/information/{user_id}', [StudentController::class, 'Student_information']);
    //grade by period and semester
    Route::get('/grades/{user_id}/{semester}/{year}', [StudentController::class, 'Grades']);
    Route::get('/footer/total/{user_id}/{semester}/{year}', [StudentController::class, 'Totalfooter']);
    Route::get('/print/{user_id}/{semester}/{year}', [StudentController::class, 'CopyOfGrades']);
    Route::get('/activated-semester', [StudentController::class, 'activated_semester']);
    Route::get('/userInfo', [StudentController::class, 'userInfo']);
    Route::get('/authUser', [StudentController::class, 'authUser']);

    Route::patch('/updateSubject/{subject}', [RegistrarController::class, 'updateSubject']);
    Route::delete('/deleteSubject/{subject}', [RegistrarController::class, 'deleteSubject']);

    Route::patch('/updateStudent/{user}', [RegistrarController::class, 'updateStudent']);

    Route::delete('/deleteAnnouncement/{announcement}', [RegistrarController::class, 'deleteAnnouncement']);

    Route::get('/mySchoolYear', [StudentController::class, 'mySchoolYear']);
    Route::get('/mySemester/{schoolYear}', [StudentController::class, 'mySemester']);
    Route::get('/myGradeForSemester/{schoolYear}/{semester}', [StudentController::class, 'myGradeForSemester']);
});

//Route::get('/print', [StudentController::class, 'print'])->name('student.print');
Route::get('/myGrade', [StudentController::class, 'myGrade'])->name('student.myGrade');
Route::get('/printGrade/{schoolYear}/{semester}', [StudentController::class, 'printGrade'])->name('student.printGrade');
