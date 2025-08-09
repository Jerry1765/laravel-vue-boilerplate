import {Notyf} from "notyf";

const notyf = new Notyf({
    duration: 3500,
    position: {
        x: 'right',
        y: 'top',
    },
    types: [
        {
            type: 'warning',
            background: 'orange',
            icon: {
                className: 'material-icons',
                tagName: 'i',
                text: 'warning'
            }
        },
        {
            type: 'info',
            background: 'blue',
            icon: {
                className: 'material-icons',
                tagName: 'i',
                text: 'info'
            }
        }
    ]
});

/**
 * @param {string} message
 */
export function showSuccessToast(message) {
    notyf.success(message);
}

/**
 * @param {string} message
 */
export function showErrorToast(message) {
    notyf.error(message);
}

/**
 * @param {string} message
 */
export function showInfoToast(message) {
    notyf.open({
        type: 'info',
        message
    });
}

/**
 * @param {string} message
 */
export function showWarningToast(message) {
    notyf.open({
        type: 'warning',
        message
    });
}
