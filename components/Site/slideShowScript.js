

window.addEventListener('load', function()
{
    this.document.querySelectorAll('.slideShowContainer').forEach( cont =>
    {
        const slides = cont.querySelectorAll('.slideImage');
        const slidesSorted = { current: Array.from(slides).sort( (a, b) => a.getAttribute("data-index") - b.getAttribute('data-index') ) };
        let currentSlide = { current: slidesSorted.current[0] };

        cont.querySelector('.prevSlideButton').onclick = () =>
        {
         currentSlide.current.classList.remove('block');
             currentSlide.current.classList.add('hidden');

            const current = Number(currentSlide.current.getAttribute('data-index'));
            if (current <= 1)
                currentSlide.current = slidesSorted.current[slidesSorted.current.length - 1];
            else
                currentSlide.current = slidesSorted.current[current - 2];

            //currentSlide.current.scrollIntoView();


             currentSlide.current.classList.remove('hidden');
             currentSlide.current.classList.add('block');
        };

        cont.querySelector('.nextSlideButton').onclick = () =>
        {
            currentSlide.current.classList.remove('block');
            currentSlide.current.classList.add('hidden');

            const current = Number(currentSlide.current.getAttribute('data-index'));

            if (current >= slidesSorted.current.length)
                currentSlide.current = slidesSorted.current[0];
            else
                currentSlide.current = slidesSorted.current[current];

            //currentSlide.current.scrollIntoView();

             currentSlide.current.classList.remove('hidden');
             currentSlide.current.classList.add('block');
        };
    });
});