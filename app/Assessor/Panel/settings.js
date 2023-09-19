
window.addEventListener('load', function()
{
    this.document.getElementById('frmSettings').onsubmit = function()
    {
        const oldPass = document.getElementById('txtOldPassword')?.value;
        if (!oldPass) return true;

        const newPass1 = document.getElementById('txtNewPassword')?.value;
        const newPass2 = document.getElementById('txtNewPassword2')?.value;

        if (!newPass1 && !newPass2)
        {
            alert('Nova senha não informada!');
            return false;
        }
        else if (newPass1 !== newPass2)
        {
            alert('Nova senha não coincide!');
            return false;
        }

        return true;
    };
});