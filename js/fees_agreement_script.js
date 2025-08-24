// FILE: js/fees_agreement_script.js

document.addEventListener('DOMContentLoaded', function() {
    const addRowBtn = document.getElementById('addRowBtn');
    
    // The maximum height for the table content area within a page (in pixels).
    // This is an estimate and may need adjustment based on content and styling.
    // A4 height (297mm) - top padding (40mm) - bottom padding (20mm) = ~237mm content area.
    // 237mm is roughly 890px. Let's use a safe value like 800px.
    const MAX_TABLE_AREA_HEIGHT = 800;
    
    let pageCount = 1;

    /**
     * Creates a new, empty table row (<tr>) with two editable cells.
     * @returns {HTMLTableRowElement} The newly created table row element.
     */
    function createRow() {
        const newRow = document.createElement("tr");
        for (let i = 0; i < 2; i++) {
            const td = document.createElement("td");
            const textarea = document.createElement("textarea");
            textarea.setAttribute("rows", "1");
            textarea.className = "editable-cell";
            // Add event listener for auto-resizing and overflow checking
            textarea.addEventListener("input", autoResizeTextarea);
            td.appendChild(textarea);
            newRow.appendChild(td);
        }
        return newRow;
    }

    /**
     * Adjusts the height of a textarea to fit its content.
     */
    function autoResizeTextarea(event) {
        const textarea = event.target;
        textarea.style.height = "auto";
        textarea.style.height = textarea.scrollHeight + "px";
        // Check for overflow after resizing
        checkTableOverflow();
    }
    
    /**
     * Adds a new row to the last table in the document.
     */
    function addRow() {
        const lastPage = document.getElementById(`page-${pageCount}`);
        const tableBody = lastPage.querySelector("tbody");
        const newRow = createRow();
        tableBody.appendChild(newRow);
        
        // Focus on the first cell of the new row
        newRow.querySelector('textarea').focus();

        // Check if the new row causes an overflow
        checkTableOverflow();
    }
    
    /**
     * Checks if the table on the last page exceeds the maximum height and moves rows if necessary.
     */
    function checkTableOverflow() {
        // Use a small timeout to allow the DOM to update before measuring height
        setTimeout(() => {
            const lastPage = document.getElementById(`page-${pageCount}`);
            const table = lastPage.querySelector(".fees-table");
            const tableBody = table.querySelector("tbody");

            while (table.offsetHeight > MAX_TABLE_AREA_HEIGHT && tableBody.rows.length > 1) {
                const lastRow = tableBody.rows[tableBody.rows.length - 1];
                const newPage = createNewPage();
                // Move the row to the new page's table
                newPage.querySelector("tbody").prepend(lastRow);
            }
        }, 0);
    }
    
    /**
     * Creates a new page element and appends it to the body.
     * @returns {HTMLElement} The newly created page element.
     */
    function createNewPage() {
        pageCount++;
        const newPage = document.createElement("div");
        newPage.className = "page";
        newPage.id = `page-${pageCount}`;

        // Get the header from the first page to duplicate it
        const headerHtml = document.querySelector(".document-header").outerHTML;
        
        // Get the table headers to duplicate them as well
        const tableHeadHtml = document.querySelector(".fees-table thead").outerHTML;

        newPage.innerHTML = `
            ${headerHtml}
            <table class="fees-table" id="fees-table-${pageCount}">
                ${tableHeadHtml}
                <tbody></tbody>
            </table>
        `;
        document.body.appendChild(newPage);
        return newPage;
    }

    // Attach event listener to the "Add Row" button
    if (addRowBtn) {
        addRowBtn.addEventListener('click', addRow);
    }
    
    // Attach input listeners to all existing textareas
    document.querySelectorAll('.editable-cell').forEach(textarea => {
        textarea.addEventListener('input', autoResizeTextarea);
        // Initial resize for pre-filled content
        autoResizeTextarea({ target: textarea });
    });
});
