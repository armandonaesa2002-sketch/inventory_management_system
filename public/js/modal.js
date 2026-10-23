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

//ASSET DATATYPE

function openDatatypeModal() {
    const form = document.getElementById("datatypeForm");

    form.action = "/device_type";

    document.getElementById("datatypeModalTitle").textContent =
        "Add Device Type";

    document.getElementById("datatypeModalText").textContent =
        "Configure a new device type.";

    document.getElementById("datatypeMethod").value = "POST";
    document.getElementById("datatypeSubmit").textContent = "Save Device Type";

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
    document.getElementById("datatypeSubmit").textContent =
        "Update Device Type";

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

//DEPARTMENT MODAL
function openDepartmentModal() {
    const form = document.getElementById("departmentForm");

    form.action = "/department";

    document.getElementById("departmentModalTitle").textContent =
        "Add Department";

    document.getElementById("departmentModalText").textContent =
        "Configure a new department.";

    document.getElementById("departmentMethod").value = "POST";
    document.getElementById("departmentSubmit").textContent = "Save department";

    form.reset();

    document.getElementById("addDepartmentModal").style.display = "block";
}
function closeDepartmentModal() {
    const modal = document.getElementById("addDepartmentModal");
    const form = document.getElementById("departmentForm");

    modal.style.display = "none";
    form.reset();

    const message = document.getElementById("departmentMessage");
    if (message) {
        message.style.display = "none";
    }
}

function openEditDepartmentModal(button) {
    const id = button.dataset.deptid;
    const form = document.getElementById("departmentForm");

    form.action = `/department/${id}`;

    document.getElementById("departmentModalTitle").textContent =
        "Edit Department";

    document.getElementById("departmentModalText").textContent =
        "Update the selected department.";

    document.getElementById("departmentMethod").value = "PUT";
    document.getElementById("departmentSubmit").textContent =
        "Update department";

    document.getElementById("dept_code").value = button.dataset.deptcode;
    document.getElementById("dept_name").value = button.dataset.deptname;

    document.getElementById("addDepartmentModal").style.display = "block";
}

// Close kapag nag-click sa labas ng modal
document.addEventListener("click", function (event) {
    const modal = document.getElementById("addDepartmentModal");

    if (modal && event.target === modal) {
        closeDepartmentModal();
    }
});

// Close kapag pinindot ang Escape
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeDepartmentModal();
    }
});

//PREPAREDBY MODAL
function openPreparedbyModal() {
    const form = document.getElementById("preparedbyForm");

    form.action = "/preparedby";

    document.getElementById("preparedbyModalTitle").textContent = "Add Name";

    document.getElementById("preparedbyModalText").textContent =
        "Configure a new name.";

    document.getElementById("preparedbyMethod").value = "POST";
    document.getElementById("preparedbySubmit").textContent = "Save Name";

    form.reset();

    document.getElementById("addPreparedbyModal").style.display = "block";
}
function closePreparedbyModal() {
    const modal = document.getElementById("addPreparedbyModal");
    const form = document.getElementById("preparedbyForm");

    modal.style.display = "none";
    form.reset();

    const message = document.getElementById("preparedbyMessage");
    if (message) {
        message.style.display = "none";
    }
}

function openEditPreparedbyModal(button) {
    const id = button.dataset.prepid;
    const form = document.getElementById("preparedbyForm");

    form.action = `/preparedby/${id}`;

    document.getElementById("preparedbyModalTitle").textContent = "Edit Name";

    document.getElementById("preparedbyModalText").textContent =
        "Update the selected name.";

    document.getElementById("preparedbyMethod").value = "PUT";
    document.getElementById("preparedbySubmit").textContent = "Update Name";

    document.getElementById("prep_code").value = button.dataset.prepcode;
    document.getElementById("prep_name").value = button.dataset.prepname;

    document.getElementById("addPreparedbyModal").style.display = "block";
}

// Close kapag nag-click sa labas ng modal
document.addEventListener("click", function (event) {
    const modal = document.getElementById("addPreparedbyModal");

    if (modal && event.target === modal) {
        closePreparedbyModal();
    }
});

// Close kapag pinindot ang Escape
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closePreparedbyModal();
    }
});

//CHANGE PASSWORD MODAL
function openChangePasswordModal() {
    const form = document.getElementById("changePasswordForm");
    form.reset();
    document.getElementById("changePasswordModal").style.display = "block";
}
function closeChangePasswordModal() {
    const modal = document.getElementById("changePasswordModal");
    const form = document.getElementById("changePasswordForm");

    modal.style.display = "none";
    form.reset();

    const message = document.getElementById("changePasswordMessage");
    if (message) {
        message.style.display = "none";
    }
}

// Close kapag nag-click sa labas ng modal
document.addEventListener("click", function (event) {
    const modal = document.getElementById("changePasswordModal");

    if (modal && event.target === modal) {
        closeChangePasswordModal();
    }
});

// Close kapag pinindot ang Escape
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeChangePasswordModal();
    }
});
