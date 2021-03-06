import axios from "axios";

const prefix = "/api";

export default {
    login: "/login",
    courses: () => axios.get(prefix + "/courses"),
    destroyCourse: (id) => axios.delete(prefix + "/courses/" + id),
    courseStore: prefix + "/courses",
    departmentStore: prefix + "/departments",
    destroyDepartment: (id) => axios.delete(prefix + "/departments/" + id),
    departments: () => axios.get(prefix + "/departments"),
    roles: () => axios.get(prefix + "/roles"),
    periods: () => axios.get(prefix + "/periods"),
    yearLevels: () => axios.get(prefix + "/year-levels"),
    teacherStore: prefix + "/teachers",
    teacherIndex: (data) => axios.get(prefix + "/teachers", { params: data }),
    subjectStore: prefix + "/subject",
    yearStore: prefix + "/year",
    yearIndex: () => axios.get(prefix + "/year"),
    activateSemester: (id) => axios.patch(prefix + "/semester/" + id),
    teacherInfo: (id) => axios.get(prefix + "/teachers/" + id),
    deleteTeacher: (id) => axios.delete(prefix + "/teachers/" + id),
    updateUserStatus: (id) => axios.patch(prefix + "/user-status/" + id),
    students: (data) => axios.get(prefix + "/students", { params: data }),
    storeStudent: prefix + "/students",
    bulkStudents: (data) => axios.post(prefix + "/students-bulk", data),
    bulkSubjects: (data) => axios.post(prefix + "/subjects-bulk", data),
    destroyStudent: (id) => axios.delete(prefix + "/students/" + id),
    departmentSubjects: () => axios.get(prefix + "/department-subjects"),
    updateSubjectStatus: (data, id) => axios.patch(prefix + "/updateSubjectStatus/" + id, data),
    getTeacherSubjects: () => axios.get(prefix + "/mySubjects"),
    studentWithoutSubject: (id, data) => axios.get(prefix + "/studentWithoutSubject/" + id, { params: data }),
    storeStudentSubject: (data) => axios.post(prefix + "/storeStudentSubject", data),
    destroyStudentSubject: (id, data) => axios.delete(prefix + "/destroyStudentSubject/" + id, { params: data }),
    studentOfMySubject: (id, data) => axios.get(prefix + "/studentOfMySubject/" + id, { params: data }),
    studentGrade: (student, subject) => axios.get(prefix + `/studentGrade/${student}/${subject}`),
    updateGrade: (student, subject, data) => axios.patch(prefix + `/updateGrade/${student}/${subject}`, data),
    teacherSubjects: (teacher_id) => axios.get(prefix + "/teacherSubjects/" + teacher_id),
    subjectStudentsGrade: (id, data) => axios.get(prefix + "/subjectStudentsGrade/" + id, { params: data }),
    approveGrade: (subject_id, data) => axios.patch(prefix + "/approveGrade/" + subject_id, data),
    approveAllGrade: (subject_id, data) => axios.patch(prefix + "/approveAllGrade/" + subject_id, data),
    announcementStore: prefix + "/announcement",
    announcement: (data) => axios.get(prefix + "/announcement/", { params: data }),
    updateGrades: (data) => axios.patch(prefix + "/updateGrades", data),
    updateSubject: (id) => prefix + "/updateSubject/" + id,
    deleteSubject: (id) => axios.delete(prefix + "/deleteSubject/" + id),
    updateStudent: (id) => prefix + "/updateStudent/" + id,
    deleteAnnouncement: (id) => axios.delete(prefix + "/deleteAnnouncement/" + id),
    mySchoolYear: () => axios.get(prefix + "/mySchoolYear"),
    mySemester: (id) => axios.get(prefix + "/mySemester/" + id),
    myGradeForSemester: (year, sem) => axios.get(prefix + `/myGradeForSemester/${year}/${sem}`),
    getAuthUser: () => axios.get(prefix + "/authUser"),
    getAssistanceStudents: (data) => axios.get(prefix + "/technical-assistance-only/students", { params: data }),
    approveResubmission: (subject, resubmission, data) => axios.patch(prefix + `/resubmission/${subject}/${resubmission}`, data),
    approveAllResubmission: (subject, data) => axios.put(prefix + `/resubmission/${subject}`, data),
}