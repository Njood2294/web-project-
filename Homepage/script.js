//show
window.onload = function () {
    document.getElementById('load').style.display = 'flex';
};

function Select(role) {
    //hide
    document.getElementById('load').style.display = 'none';

    //show2
    if (role === 'patient') {
        document.getElementById('patientForm').style.display = 'block';
    } else if (role === 'doctor') {
        document.getElementById('doctorForm').style.display = 'block';
    }
}
