const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const loginModal = document.getElementById('loginModal');

        openModalBtn.addEventListener('click', () => {
            loginModal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', () => {
            loginModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === loginModal) {
                loginModal.style.display = 'none';
            }
        });