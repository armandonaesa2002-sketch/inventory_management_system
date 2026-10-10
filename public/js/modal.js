function openCompleteModal() {
    document.getElementById("completeRepairModal").style.display = "block";
}

function closeCompleteModal() {
    document.getElementById("completeRepairModal").style.display = "none";
}

// kung marami buttons:
document.querySelectorAll(".modalreturn").forEach((button) => {
    button.addEventListener("click", function () {
        let id = this.dataset.id;
        document.getElementById("modalRepairId").value = this.dataset.id;
        document.getElementById("modalAssetTag").textContent =
            this.dataset.asset;
        document.getElementById("completeRepairForm").action =
            `/inventory/${id}/mark_as_complete`;
        document.getElementById("completeRepairModal").style.display = "block";
    });
});

function openDatatypeModal() {
    document.getElementById("addDatatypeModal").style.display = "block";
}

function closeDatatypeModal() {
    document.getElementById("addDatatypeModal").style.display = "none";
}

// Close kapag nag-click sa labas ng modal
document.addEventListener("click", function (event) {
    const modal = document.getElementById("addDatatypeModal");

    if (modal && event.target === modal) {
        closeDatatypeModal();
    }
});

// Close kapag pinindot ang Escape
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeDatatypeModal();
    }
});
