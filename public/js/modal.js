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
    const form = document.getElementById("datatypeForm");

    form.action = "/device_type";

    document.getElementById("datatypeModalTitle").textContent =
        "Add Device Type";

    document.getElementById("datatypeModalText").textContent =
        "Configure a new device type.";

    document.getElementById("datatypeMethod").value = "POST";
    document.getElementById("datatypeSubmit").textContent = "Save Datatype";

    form.reset();

    document.getElementById("has_hardware").checked = false;
    document.getElementById("has_purchase").checked = false;
    document.getElementById("has_license_notes").checked = false;

    document.getElementById("addDatatypeModal").style.display = "block";
}

function openEditDatatypeModal(button) {
    const id = button.dataset.id;
    const form = document.getElementById("datatypeForm");

    form.action = `/device_type/${id}`;

    document.getElementById("datatypeModalTitle").textContent =
        "Edit Device Type";

    document.getElementById("datatypeModalText").textContent =
        "Update the selected device type.";

    document.getElementById("datatypeMethod").value = "PUT";
    document.getElementById("datatypeSubmit").textContent = "Update Datatype";

    document.getElementById("code").value = button.dataset.code;
    document.getElementById("name").value = button.dataset.name;
    document.getElementById("asset_prefix").value = button.dataset.prefix;

    document.getElementById("has_hardware").checked =
        button.dataset.hardware === "1";

    document.getElementById("has_purchase").checked =
        button.dataset.purchase === "1";

    document.getElementById("has_license_notes").checked =
        button.dataset.license === "1";

    document.getElementById("addDatatypeModal").style.display = "block";
}

function closeDatatypeModal() {
    document.getElementById("addDatatypeModal").style.display = "none";
    const message = document.getElementById("datatypeMessage");

    if (message) {
        message.style.display = "none";
    }
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
