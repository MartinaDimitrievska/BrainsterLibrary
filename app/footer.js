document.addEventListener('DOMContentLoaded', function () {
    fetch('http://api.quotable.io/random')
        .then(response => response.json())
        .then(data => {
            const footer = document.createElement('footer');
            footer.className = 'bg-gray-800 text-white p-4 text-center';
            footer.style.position = 'relative';
            footer.style.bottom = '0';
            footer.style.width = '100%';


            const quoteContainer = document.createElement('div');
            quoteContainer.id = 'quote-container';
            
            const quoteParagraph = document.createElement('p');
            quoteParagraph.textContent = `${data.content} - ${data.author}`;

            quoteContainer.appendChild(quoteParagraph);
            footer.appendChild(quoteContainer);
            
            document.body.appendChild(footer);
        })
        .catch(error => console.error('Error fetching random quote:', error));
});