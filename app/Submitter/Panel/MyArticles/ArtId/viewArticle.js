
window.addEventListener('load', function()
{
    if (this.document.getElementById('frmUploadIdded'))
        this.document.getElementById('frmUploadIdded').onsubmit = function()
        {
            const fileInput = document.getElementById('fileArticleIdded');
            if (fileInput.files[0])
            {
                if (fileInput.files[0].size > 10 * 1024 * 1024)
                {
                    alert("Tamanho máximo de 10MB excedido!");
                    return false;
                }
            }
            return true;
        };
});