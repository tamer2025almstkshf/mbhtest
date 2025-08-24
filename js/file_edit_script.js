// FILE: js/file_edit_script.js

document.addEventListener('DOMContentLoaded', function() {

    /**
     * Opens a popup window with specified URL and dimensions.
     * @param {string} url - The URL to open in the popup.
     */
    window.openPopup = function(url) {
        const width = 800;
        const height = 600;
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);
        const options = `resizable=yes,width=${width},height=${height},top=${top},left=${left},scrollbars=yes`;
        window.open(url, 'PopupWindow', options);
    };

    /**
     * Toggles the visibility of extra form fields for clients or opponents.
     * @param {string} type - 'clients' or 'opponents'.
     */
    window.toggleExtraFields = function(type) {
        const fields = (type === 'clients') 
            ? document.querySelectorAll('.extra-client-field')
            : document.querySelectorAll('.extra-opponent-field');
        
        fields.forEach(field => {
            field.style.display = (field.style.display === 'none' || field.style.display === '') ? 'block' : 'none';
        });
    };
    
    // Logic to preserve scroll position across page reloads
    const scrollContainer = document.getElementById("scrollContainer");
    if (scrollContainer) {
        // On page load, try to restore the scroll position
        const scrollPos = localStorage.getItem("fileEditScrollPos");
        if (scrollPos) {
            scrollContainer.scrollTop = parseInt(scrollPos, 10);
        }

        // Before the page is unloaded, save the current scroll position
        window.addEventListener("beforeunload", function () {
            localStorage.setItem("fileEditScrollPos", scrollContainer.scrollTop);
        });
    }

});
