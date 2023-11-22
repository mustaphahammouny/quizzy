/*
 * Main and demo navigation arrays
 *
 * 'to' attribute points to the route name, not the path url
 */

export default {
    teacher: [
        {
            name: "dashboard",
            to: "teacher.dashboard",
            icon: "si si-speedometer",
        },
        {
            name: "quizzes",
            to: "teacher.quizzes.index",
            icon: "fa fa-list",
        },
        {
            name: "students",
            to: "teacher.students.index",
            icon: "fa fa-users",
        },
    ],
    student: [
        {
            name: "dashboard",
            to: "student.dashboard",
            icon: "si si-speedometer",
        },
        {
            name: "quizzes",
            to: "student.quizzes.index",
            icon: "fa fa-list",
        },
        {
            name: "favorite quizzes",
            to: "student.quizzes.favorite",
            icon: "fa fa-heart",
        },
        {
            name: "passed quizzes",
            to: "student.quizzes.passed",
            icon: "fa fa-check-circle",
        },
    ],
};
