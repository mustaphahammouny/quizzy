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
            to: "teacher.quizzes",
            icon: "si si-note",
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
            to: "student.quizzes",
            icon: "si si-note",
        },
    ],
};
