<?php

use App\Http\Controllers\PageHandlerController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;


Route::redirect('/','/login');

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);


Route::get('/tester',function(){
    return "what the fuck";
});

Route::get('/handler',[PageHandlerController::class,'handle']);

//Registrar Routes
Route::get('/registrar-subject',[RegistrarController::class,'createSubject'])->name('registrar.subject');
Route::get('/registrar',[RegistrarController::class,'index'])->name('registrar.index');
Route::get('/registrar-year-semester',[RegistrarController::class,'yearSemesterView'])->name('registrar.yearSemesterView');
Route::get('/registrar-student',[RegistrarController::class,'studentView'])->name('registrar.studentView');
Route::get('/registrar-teacher',[RegistrarController::class,'teacherView'])->name('registrar.teacherView');
Route::get('/registrar-courses',[RegistrarController::class,'setup'])->name('registrar.setup');
Route::get('/registrar-announcement',[RegistrarController::class,'announcementIndex'])->name('registrar.announcement');

//Teacher Routes
Route::get('/teacher',[TeacherController::class,'index'])->name('teacher.index');
Route::get('/teacher-subject',[TeacherController::class,'createSubject'])->name('teacher.subject');
Route::get('/teacher-assign-student-to-subject',[TeacherController::class,'assignStudentsToSubjects'])->name('teacher.assignStudentsToSubjects');

//Student Routes
Route::get('/student',[StudentController::class,'index'])->name('student.index');
Route::get('/announcement',[StudentController::class,'announcementIndex'])->name('student.announcement');

Route::get('/studentProfile/{student}',[RegistrarController::class,'studentProfile'])->name('student.profile');






















/* Group Routes */
Route::prefix('api')->group(function () {
    
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
Route::get('/periods', [AuthController::class, 'periodsIndex']);
//Roles
Route::get('/roles', [AuthController::class, 'rolesIndex']);
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
Route::get('/announcement', [AuthController::class, 'announcement']);


//teachers
Route::post('/subjects/acquired', [\App\Http\Controllers\TeacherController::class, 'Acquired_subjects']);
Route::post('/view/subjects/cs/{user_id}', [\App\Http\Controllers\TeacherController::class, 'View_subject_cs_department']);
Route::post('/view/subjects/coe', [\App\Http\Controllers\TeacherController::class, 'View_subject_coe_department']);

Route::post('/subjects/acquired', [TeacherController::class, 'Acquired_subjects']);
Route::post('/view/subjects/cs', [TeacherController::class, 'View_subject_cs_department']);
Route::post('/view/subjects/coe', [TeacherController::class, 'View_subject_coe_department']);

//students

Route::post('/student/information/{user_id}', [\App\Http\Controllers\StudentController::class, 'Student_information']);
//grade by period and semester
Route::get('/grades/{user_id}/{semester}/{year}', [\App\Http\Controllers\StudentController::class, 'Grades']);
Route::get('/footer/total/{user_id}/{semester}/{year}', [\App\Http\Controllers\StudentController::class, 'Totalfooter']);
Route::get('/print/{user_id}', [\App\Http\Controllers\StudentController::class, 'CopyOfGrades']);
});


/* Redirector */

Route::redirect('/teacher', '/teacher-subject');