'use strict';

const answers = document.getElementsByClassName('answer');
const toggleCheckbox = function (checkbox) {
    if (checkbox.checked) {
        checkbox.checked = false;
    } else {
        checkbox.checked = true;
    }
};
[...answers].forEach((answer) =>
    answer.addEventListener('click', function (event) {
        const checkbox = this.querySelector('.answer__check');
        toggleCheckbox(checkbox);
    })
);
