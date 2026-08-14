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
