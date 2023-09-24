
window.addEventListener('load', function()
{
    const olAuthors = this.document.getElementById('olAuthors');
    const hidAuthorsJson = this.document.getElementById('hidAuthorsJson');

    function addAuthor(name)
    {
        const newLi = document.createElement('li');
        const txt = document.createElement('input');
        txt.type = 'text';
        txt.value = name;
        txt.required = true;
        txt.size = 40;
        txt.maxLength = 140;
        const removeBtn = document.createElement('button');
        removeBtn.type = "button";
        removeBtn.className = 'btn ml-2 min-w-0';
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = () => olAuthors.removeChild(newLi);

        newLi.appendChild(txt);
        newLi.appendChild(removeBtn);
        olAuthors.appendChild(newLi);
    }

    this.document.getElementById('btnAddAuthor').onclick = function()
    {
        addAuthor('');
    };

    this.document.getElementById('frmCreateArticle').onsubmit = function()
    {
        const names = Array.from(olAuthors.querySelectorAll('input[type="text"]')).map( inp => inp.value ).filter(Boolean);
        hidAuthorsJson.value = JSON.stringify(names);

        const fileInput = document.getElementById('fileArticleNotIdded');
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

    addAuthor(this.document.getElementById('hidSubmitterName')?.value ?? '');
});