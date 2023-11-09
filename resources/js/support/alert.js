import Swal from "sweetalert2";

let toast = Swal.mixin({
    buttonsStyling: false,
    target: "#page-container",
    customClass: {
        confirmButton: "btn btn-success m-1",
        cancelButton: "btn btn-danger m-1",
        input: "form-control",
    },
});

const confirm = (params) => {
    toast
        .fire({
            title: "Are you sure?",
            text: params.text,
            icon: "warning",
            showCancelButton: true,
            customClass: {
                confirmButton: "btn btn-danger m-1",
                cancelButton: "btn btn-secondary m-1",
            },
            confirmButtonText: "Yes, i'm sure!",
            html: false,
            preConfirm: () => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve();
                    }, 50);
                });
            },
        })
        .then((result) => {
            if (result.value) {
                params.callback();
            }
        });
};

export default {
    confirm,
};
