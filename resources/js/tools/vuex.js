import api from './api';
import Vuex from 'vuex'
window.Vue = require('vue').default;
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        courses: [],
        departments: [],
        yearLevels: [],
        roles: [],
        periods: [],
        teachers: [],
        schoolYears: [],
        dialog: {message: null, state: null, value: null},
        semester : null
    },
    mutations: { //Synchronous
        setYearLevels(state,payload){
            state.yearLevels = payload
        },
        setRoles(state,payload){
            state.roles = payload
        },
        setPeriods(state,payload){
            state.periods = payload
        },
        setCourses(state,payload){
            state.courses = payload
        },
        pushToCourses(state,payload){
            state.courses.push(payload)
        },
        setDepartments(state,payload){
            state.departments = payload
        },
        pushToDepartments(state,payload){
            state.departments.push(payload)
        },
        setDialog(state,payload){
            state.dialog = payload
        },
        setTeachers(state,payload){
            state.teachers = payload
        },
        setTeacherRowsPerPage(state,payload){
            state.teachers.rowsPerPage = payload
        },
        setSchoolYears(state,payload){
            state.schoolYears = payload
        },
        pushSchoolYear(state,payload){
            state.schoolYears.push(payload)
        },
        setSemester(state,payload){
            state.semester = payload
        }
    },
    actions: { //Async
        setYearLevels(state,payload){
            state.commit('setYearLevels',payload)
        },
        setRoles(state,payload){
            state.commit('setRoles',payload)
        },
        setPeriods(state,payload){
            state.commit('setPeriods',payload)
        },
        setCourses(state,payload){
            state.commit('setCourses',payload)
        },
        setDepartments(state,payload){
            state.commit('setDepartments',payload)
        },
        pushToDepartments(state,payload){
            state.commit('pushToDepartments',payload)
        },
        pushToCourses(state,payload){
            state.commit('pushToCourses',payload)
        },
        setDialog(state, payload) {
            state.commit('setDialog',payload)
        },
        setTeachers(state,payload){
            state.commit('setTeachers',payload)
        },
        setTeacherRowsPerPage(state,payload){
            state.commit('setTeacherRowsPerPage',payload)
        },
        setSchoolYears(state,payload){
            state.commit('setSchoolYears',payload)
        },
        pushSchoolYear(state,payload){
            state.commit('pushSchoolYear',payload)
        },
        setSemester(state,payload){
            state.commit('setSemester',payload)
        }
    },
    getters: {
        getYearLevels: state => state.yearLevels,
        getRoles: state => state.roles,
        getPeriods: state => state.periods,
        getCourses: state => state.courses,
        getDepartments: state => state.departments,
        getDialog : state => state.dialog,
        getTeachers : state => state.teachers,
        getSelectedTeachers: state => state.selectedTeachers,
        getSchoolYears: state => state.schoolYears,
        getSemester: state => state.semester
    }
})