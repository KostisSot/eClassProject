// =========================================================================
// ΔΙΑΧΕΙΡΙΣΗ ΠΡΟΦΙΛ ΧΡΗΣΤΗ & ΔΙΚΑΙΩΜΑΤΩΝ ΠΡΟΣΒΑΣΗΣ
// =========================================================================
document.addEventListener("DOMContentLoaded", function() {
    const nameElem = document.querySelector('.user-name');
    const roleElem = document.querySelector('.user-role');
    const avatarWrapper = document.querySelector('.avatar-wrapper');

    if (typeof globalUserData !== 'undefined') {
        if (nameElem) nameElem.textContent = globalUserData.fullName;
        if (roleElem) roleElem.textContent = globalUserData.role;

        if (avatarWrapper) {
            avatarWrapper.setAttribute('data-tooltip', globalUserData.fullName);
        }

        const adminLink = document.getElementById('control-panel-link');
        if (adminLink) {
            //lowercase και καθαρίζουμε κενά για να μην αποτύχει η σύγκριση
            const role = globalUserData.role.trim().toLowerCase();

            if (role === 'tutor') {
                adminLink.style.display = 'block';
            } else {
                adminLink.style.display = 'none';
            }
        }
    }
});

window.addEventListener( "pageshow", function ( event ) {
    var historyTraversal = event.persisted ||
        ( typeof window.performance != "undefined" &&
            window.performance.navigation.type === 2 );
    if ( historyTraversal ) {
        window.location.reload();
    }
});


// ==========================================
// ΔΙΑΧΕΙΡΙΣΗ MODALS
// ==========================================

// Επεξεργασία Ανακοινώσεων
function openEditModal(id, subject, content) {
    var modal = document.getElementById('editModal');
    if (modal) {
        modal.style.display = 'block';
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-subject').value = subject;
        document.getElementById('edit-content').value = content;
    }
}

function closeEditModal() {
    var modal = document.getElementById('editModal');
    if (modal) modal.style.display = 'none';
}

function insertLink(textareaId) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;

    const url = prompt("Εισάγετε το URL (π.χ. https://google.com):", "https://");
    const text = prompt("Εισάγετε το κείμενο του συνδέσμου:", "Κάντε κλικ εδώ");

    if (url && text) {
        const linkCode = `<a href=${url} target="_blank">${text}</a>`;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const currentContent = textarea.value;

        textarea.value = currentContent.substring(0, start) + linkCode + currentContent.substring(end);

        textarea.focus();
    }
}
//Προσθήκη Εγγράφου
function openAddModal() {
    var modal = document.getElementById('addDocModal');
    if (modal) modal.style.display = 'block';
}

function closeAddModal() {
    var modal = document.getElementById('addDocModal');
    if (modal) modal.style.display = 'none';
}

//Προσθήκης Εργασίας
function openHomeworkModal() {
    var modal = document.getElementById('addHomeworkModal');
    if (modal) modal.style.display = 'block';
}

function closeHomeworkModal() {
    var modal = document.getElementById('addHomeworkModal');
    if (modal) modal.style.display = 'none';
}

// Κλείσιμο σε κλικ έξω από την κάρτα
window.onclick = function(event) {
    var editModal = document.getElementById('editModal');
    var addDocModal = document.getElementById('addDocModal');
    var addHomeworkModal = document.getElementById('addHomeworkModal');

    if (editModal && event.target === editModal) {
        editModal.style.display = "none";
    }
    if (addDocModal && event.target === addDocModal) {
        addDocModal.style.display = "none";
    }
    if (addHomeworkModal && event.target === addHomeworkModal) {
        addHomeworkModal.style.display = "none";
    }
}

// controlPanel
function openUserModal() {
    const m = document.getElementById('addUserModal');
    if(m) m.style.display = 'block';
}

function closeUserModal() {
    const m = document.getElementById('addUserModal');
    if(m) m.style.display = 'none';
}

// ==========================================
// TOAST NOTIFICATIONS
// ==========================================
function showNotification(message, type = 'info') {
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<span>${message}</span>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 500);
    }, 4000);

    toast.onclick = () => toast.remove();
}

//Scroll to the top button
function scrollToTop() {
    const scrollContainer = document.querySelector('.homework_main-layout')
        || document.querySelector('.announcement_main-layout')
        || document.querySelector('.doc_main-layout');

    if (scrollContainer) {
        scrollContainer.scrollTo({
            top: 0,
            behavior: 'smooth' // Ομαλή κίνηση
        });
    }
}