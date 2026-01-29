/* File: public/js/page-magang.js */

document.addEventListener('DOMContentLoaded', function () {
    const elements = {
        searchInput: document.getElementById('searchInput'),
        kampusSelect: document.getElementById('kampusSelect'),
        yearSelect: document.getElementById('yearSelect'),
        searchLoader: document.getElementById('searchLoader'),
        kampusLoader: document.getElementById('kampusLoader'),
        yearLoader: document.getElementById('yearLoader'),
        resultsContainer: document.getElementById('magang-container'),
        gridBtn: document.getElementById('view-grid-btn'),
        rowBtn: document.getElementById('view-row-btn'),
        body: document.body,
        filterForm: document.getElementById('filterForm'),
        
        // Modal Elements (Untuk Fallback jika tidak ada SweetAlert)
        modal: document.getElementById('deleteModal'),
        modalBackdrop: document.getElementById('modalBackdrop'),
        modalPanel: document.getElementById('modalPanel'),
        confirmBtn: document.getElementById('confirmDeleteBtn'),
        cancelBtn: document.getElementById('cancelDeleteBtn')
    };

    let searchTimeout;
    let targetFormId = null;

    // --- 1. View Toggler Logic ---
    function initViewToggle() {
        if (!elements.gridBtn || !elements.rowBtn) return;

        // Scroll horizontal dengan mouse wheel saat mode row
        if (elements.resultsContainer) {
            elements.resultsContainer.addEventListener('wheel', (e) => {
                if (elements.body.classList.contains('view-row') && e.deltaY !== 0) {
                    e.preventDefault();
                    elements.resultsContainer.scrollLeft += e.deltaY;
                }
            });
        }

        elements.gridBtn.addEventListener('click', () => setView('grid'));
        elements.rowBtn.addEventListener('click', () => setView('row'));
    }

    function setView(mode) {
        if (mode === 'grid') {
            elements.body.classList.add('view-grid');
            elements.body.classList.remove('view-row');
            elements.gridBtn.classList.add('active');
            elements.rowBtn.classList.remove('active');
        } else {
            elements.body.classList.add('view-row');
            elements.body.classList.remove('view-grid');
            elements.rowBtn.classList.add('active');
            elements.gridBtn.classList.remove('active');
        }
    }

    // --- 2. Filter Logic (AJAX) ---
    function toggleLoaders(show) {
        const display = show ? 'flex' : 'none';
        if (elements.searchLoader) elements.searchLoader.style.display = display;
        if (elements.kampusLoader) elements.kampusLoader.style.display = display;
        if (elements.yearLoader) elements.yearLoader.style.display = display;
    }

    function performFilter() {
        const params = new URLSearchParams();
        if (elements.searchInput.value) params.append('search', elements.searchInput.value);
        if (elements.kampusSelect.value) params.append('kampus', elements.kampusSelect.value);
        if (elements.yearSelect.value && elements.yearSelect.value !== 'all') params.append('year', elements.yearSelect.value);

        let loaderTimeout = setTimeout(() => toggleLoaders(true), 300);
        const currentUrl = elements.filterForm.getAttribute('action'); 

        fetch(`${currentUrl}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
        })
        .then(response => response.text())
        .then(html => {
            clearTimeout(loaderTimeout);
            toggleLoaders(false);
            updateDOM(html);
            
            const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
            window.history.pushState({}, '', newUrl);
        })
        .catch(error => {
            console.error('Filter error:', error);
            clearTimeout(loaderTimeout);
            toggleLoaders(false);
        });
    }

    function updateDOM(html) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        const newContainer = doc.getElementById('magang-container');
        const oldContainer = document.getElementById('magang-container');
        
        if (newContainer && oldContainer) {
            oldContainer.innerHTML = newContainer.innerHTML;
        } else if (newContainer && !oldContainer) {
            const currentEmptyState = document.querySelector('.empty-state');
            if(currentEmptyState) currentEmptyState.remove();
            
            const pagination = document.querySelector('.mt-8');
            if(pagination) {
                pagination.before(newContainer);
            } else {
                 const filterContainer = document.querySelector('.filter-container');
                 if(filterContainer) filterContainer.after(newContainer);
            }
        } else if (!newContainer && doc.querySelector('.empty-state')) {
             if(oldContainer) oldContainer.remove();
             const existingEmpty = document.querySelector('.empty-state');
             if(!existingEmpty) {
                 const filterContainer = document.querySelector('.filter-container');
                 if(filterContainer) filterContainer.after(doc.querySelector('.empty-state'));
             }
        }

        // Update Filter Info & Pagination
        const newFilterInfo = doc.querySelector('.filter-info');
        const oldFilterInfo = document.querySelector('.filter-info');
        if (newFilterInfo) {
            if (oldFilterInfo) oldFilterInfo.innerHTML = newFilterInfo.innerHTML;
            else elements.filterForm.appendChild(newFilterInfo);
        } else if (oldFilterInfo) {
            oldFilterInfo.remove();
        }

        const newPagination = doc.querySelector('.mt-8'); 
        const oldPagination = document.querySelector('.mt-8');
        if (newPagination) {
             if(oldPagination) oldPagination.innerHTML = newPagination.innerHTML;
        } else if (oldPagination) {
            oldPagination.remove();
        }
    }

    // --- 3. Event Listeners ---
    function initListeners() {
        if (elements.searchInput) {
            elements.searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performFilter, 600);
            });
        }
        if (elements.kampusSelect) elements.kampusSelect.addEventListener('change', performFilter);
        if (elements.yearSelect) elements.yearSelect.addEventListener('change', performFilter);
    }

    // --- 4. ALERT SUKSES (Menggunakan SweetAlert2 Toast) ---
    function initAlertDismiss() {
        const successAlert = document.querySelector('.success-alert');
        
        // Ambil pesan teks dari dalam elemen alert
        if (successAlert) {
            const message = successAlert.innerText.trim();

            // Cek apakah SweetAlert2 tersedia (Swal)
            if (typeof Swal !== 'undefined') {
                // Sembunyikan alert bawaan/lama
                successAlert.style.display = 'none';

                // Tampilkan Toast SweetAlert
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#2a2a2a', // Sesuaikan tema gelap
                    color: '#fff',
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            } else {
                // Fallback: Gunakan animasi fade-out lama jika Swal tidak ada
                setTimeout(() => {
                    successAlert.classList.add('fade-out');
                    setTimeout(() => successAlert.remove(), 500);
                }, 3000);
            }
        }
    }

    // --- 5. DELETE CONFIRMATION (Menggunakan SweetAlert2) ---
    function initDeleteConfirmation() {
        // Event Delegation untuk tombol delete
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.trigger-delete-modal');
            
            if (btn) {
                e.preventDefault();
                targetFormId = btn.getAttribute('data-form-id'); 
                const form = document.getElementById(targetFormId);

                // Cek apakah SweetAlert2 tersedia
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#2a2a2a', // Tema gelap
                        color: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (form) form.submit();
                        }
                    });
                } else {
                    // Fallback: Gunakan Custom Modal Lama jika Swal tidak ada
                    if (elements.modal) {
                        toggleModal(true);
                    } else if (confirm('Yakin ingin menghapus data ini?')) {
                        // Fallback terakhir: Native Confirm
                        if (form) form.submit();
                    }
                }
            }
            
            // Tutup modal custom (Fallback) jika klik backdrop
            if (e.target === elements.modalBackdrop) {
                toggleModal(false);
            }
        });

        // Listener untuk tombol di dalam Modal Custom (Fallback)
        if(elements.cancelBtn) {
            elements.cancelBtn.addEventListener('click', () => toggleModal(false));
        }
        if(elements.confirmBtn) {
            elements.confirmBtn.addEventListener('click', () => {
                if (targetFormId) {
                    const form = document.getElementById(targetFormId);
                    if (form) form.submit();
                }
            });
        }
    }

    // Helper Modal Custom (Hanya dipakai jika Swal tidak ada)
    function toggleModal(show) {
        if (!elements.modal) return;
        if (show) {
            elements.modal.classList.remove('hidden');
            setTimeout(() => {
                elements.modalBackdrop.classList.remove('opacity-0');
                elements.modalPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                elements.modalPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        } else {
            elements.modalBackdrop.classList.add('opacity-0');
            elements.modalPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            elements.modalPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => {
                elements.modal.classList.add('hidden');
            }, 300);
        }
    }

    // Initialize All
    initViewToggle();
    initListeners();
    initAlertDismiss();
    initDeleteConfirmation();
});