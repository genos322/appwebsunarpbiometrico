'use strict';
document.addEventListener('DOMContentLoaded', function () {
    // Your code here
    let btn = document.getElementById('sendExcel');
    btn.addEventListener('click', function () {
        let form = document.getElementById('excelForm');
        form.submit();
    });
});