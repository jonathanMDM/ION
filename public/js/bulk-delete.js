/**
 * Bulk Delete Functionality
 * Reusable JavaScript for bulk delete with checkboxes
 */

function toggleAll(source) {
    const checkboxes = document.querySelectorAll(".row-checkbox");
    checkboxes.forEach((checkbox) => {
        checkbox.checked = source.checked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll(".row-checkbox:checked");
    const count = checkboxes.length;
    const countElement = document.getElementById("selectedCount");
    if (countElement) {
        countElement.textContent = count;
    }

    const bulkDeleteBtn = document.getElementById("bulkDeleteBtn");
    if (bulkDeleteBtn) {
        if (count > 0) {
            bulkDeleteBtn.classList.remove("hidden");
        } else {
            bulkDeleteBtn.classList.add("hidden");
        }
    }

    // Update select all checkbox
    const selectAll = document.getElementById("selectAll");
    if (selectAll) {
        const allCheckboxes = document.querySelectorAll(".row-checkbox");
        selectAll.checked =
            allCheckboxes.length > 0 && count === allCheckboxes.length;
    }
}

function confirmBulkDelete(itemName = "elemento") {
    const count = document.querySelectorAll(".row-checkbox:checked").length;
    if (
        confirm(
            `¿Estás seguro de que deseas eliminar ${count} ${itemName}(s)? Esta acción no se puede deshacer.`
        )
    ) {
        document.getElementById("bulkDeleteForm").submit();
    }
}
