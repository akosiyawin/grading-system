<?php


namespace App;


class Base
{
    /*Defined Constants of application*/
    const REGISTRAR_ROLE_ID = 1;
    const TEACHER_ROLE_ID = 2;
    const STUDENT_ROLE_ID = 3;

    const PRELIM_PERIOD_ID = 1;
    const MIDTERM_PERIOD_ID = 2;
    const FINALS_PERIOD_ID = 3;

    const ANNOUNCEMENT_LIMIT = 10;

    const STUDENT_DEFAULT_PASSWORD = 123;
    const TEACHER_DEFAULT_PASSWORD = 123;

    const MIN_STUDENT_GRADE = 0;
    const MAX_STUDENT_GRADE = 101;

//    courses titles
    const ACT = 'ACT';
    const BS_COE = 'BSCOE';
    const BS_HRM = 'BS HRM';
    const BS_CS = 'BSCS';
    const BS_HM = 'BS HM';
    const BS_IT = 'BSIT';
    const HRS = 'HRS';
    const HRT = 'HRT';
//    const GEN_ED = 'General Education';

//    department titles
    const CS_DEPT = 'CS Department';
    const ENG_DEPT = 'Eng Department';
    const HRM_DEPT = 'HRM Department';
    const GEN_ED_DEPT = 'General Education';

//    course_data
    const ACT_INPUT = 'ACT';
    const BS_COE_INPUT = 'BS COE';
    const BS_HRM_INPUT = 'BS HRM';
    const BS_CS_INPUT = 'BSCS';
    const BS_HM_INPUT = 'BSHM';
    const BS_IT_INPUT = 'BSIT';
    const HRS_INPUT = 'HRS';
    const HRT_INPUT = 'HRT';
    const GEN_ED_INPUT = 'General Education';

    //course_id
    const ACT_DATA = 2;
    const BS_COE_DATA = 3;
    const BS_HRM_DATA = 4;
    const BS_CS_DATA = 5;
    const BS_HM_DATA = 6;
    const BS_IT_DATA = 7;
    const HRS_DATA = 8;
    const HRT_DATA = 9;
    const GEN_ED_DATA = 1;

    const SCHOOL_YEAR_MAX = 2099;

//    grading
    const GRADE_98_100 = '1.0';
    const GRADE_95_97 = '1.25';
    const GRADE_92_94 = '1.50';
    const GRADE_89_91 = '1.75';
    const GRADE_86_88 = '2.00';
    const GRADE_83_85 = '2.25';
    const GRADE_80_82 = '2.50';
    const GRADE_77_79 = '2.75';
    const GRADE_75_76 = '3.00';
    const GRADE_lower_75 = '5.00';
    const FAILED = 'Failed';
    const INC = '2';
    const DRP = '4';
    const PASSED = 'Passed';
}
