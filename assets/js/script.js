const questions = document.querySelectorAll('.question');

questions.forEach(question => {
    question.addEventListener('click', () => {
    
        const item = question.closest('.faq-item'); 
        
        question.classList.toggle('active');

        const answer = item.querySelector('.answer');

        if (answer.style.display === 'none' || answer.style.display === '') {
            answer.style.display = 'block';
        } else {
            answer.style.display = 'none';
        }
    });
});