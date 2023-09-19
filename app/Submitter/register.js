
window.addEventListener('load', function()
{
    this.document.getElementById('frmRegister').onsubmit = function()
    {
        const newPass1 = document.getElementById('txtPassword')?.value;
        const newPass2 = document.getElementById('txtPassword2')?.value;

        if (!newPass1 || !newPass2)
        {
            alert('Senha não informada!');
            return false;
        }
        else if (newPass1 !== newPass2)
        {
            alert('Senha não coincide!');
            return false;
        }

        return true;
    };
});