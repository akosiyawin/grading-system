require('./bootstrap');

window.Vue = require('vue').default;
import vuetify from "./tools/vuetify";
import store from "./tools/vuex"



Vue.component('dialog-message', require('./base/DialogMessage').default);
Vue.component('loading', require('./base/Loading').default);

Vue.component('Login', require('./components/Login').default);
Vue.component('department-select', require('./components/DepartmentSelect').default);
Vue.component('teacher-table', require('./components/TeacherTable').default);
Vue.component('teacher-create', require('./components/AddTeacher').default);

Vue.component('student-index', require('./students/StudentIndex').default);
Vue.component('student-announcement', require('./students/Announcement').default);
Vue.component('student-grade', require('./students/MyGrade').default);
Vue.component('print-grade', require('./students/PrintGrade').default);


Vue.component('assistance', require('./Assistance').default);

//Registrar
Vue.component('registrar-courses', require('./registrar/CourseSetup').default);
Vue.component('registrar-department', require('./registrar/DepartmentSetup').default);
Vue.component('registrar-static', require('./registrar/StaticSetup').default);
Vue.component('registrar-subject', require('./registrar/Subject').default);
Vue.component('registrar-yearsem', require('./registrar/YearSem').default);
Vue.component('registrar-student', require('./registrar/Student').default);
Vue.component('registrar-announcement', require('./registrar/Announcement').default);
Vue.component('jumbotron', require('./base/Jumbotron').default);

//Teacher
Vue.component('teacher-subject', require('./teacher/Subject').default);
Vue.component('teacher-student', require('./teacher/Student').default);

const app = new Vue({
    el: '#app',
    vuetify,
    store
});
