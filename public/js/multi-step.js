// WIZARD STYLE
document.addEventListener("DOMContentLoaded", function () {
    const originalDeviceType = document.getElementById(
        "original_device_type",
    ).value;
    const originalAssetTag =
        document.getElementById("original_asset_tag").value;
    const assetTagInput = document.getElementById("asset_tag");
    const isEdit = document.getElementById("is_edit").value == "1";

    // MOVED UP: kailangan ito bago gamitin sa change handler
    let currentStep = 0;
    const steps = document.querySelectorAll(".step");

    document
        .getElementById("device_type")
        .addEventListener("change", function () {
            const currentValue = this.value;

            // NEW: Wizard-only logic (para sa ADD page lang na may .step)
            // Sa EDIT (tabs), kadalasan walang .step, so steps.length === 0 at hindi papasok dito.
            if (steps.length) {
                const hardwareStep = steps[1];
                const licenseStep = steps[3];
                const nextStep4Btn = document.getElementById("next_step_3");
                const submitStep4Btn = document.getElementById("submit_step_3");

                if (currentValue === "printer") {
                    hardwareStep.style.display = "none";
                    licenseStep.style.display = "none";

                    if (currentStep === 1) {
                        window.nextStep();
                    }
                    if (currentStep === 3) {
                        currentStep = 2;
                        showStep(currentStep);
                    }

                    if (nextStep4Btn && submitStep4Btn) {
                        nextStep4Btn.style.display = "none";
                        submitStep4Btn.style.display = "inline-block";
                    }
                } else {
                    hardwareStep.style.display = "";
                    licenseStep.style.display = "";

                    if (nextStep4Btn && submitStep4Btn) {
                        nextStep4Btn.style.display = "inline-block";
                        submitStep4Btn.style.display = "none";
                    }
                }
            }

            if (isEdit) {
                if (currentValue === originalDeviceType) {
                    assetTagInput.value = originalAssetTag;
                    return;
                }

                if (currentValue) {
                    fetch(`/inventory/asset_tag/${currentValue}`)
                        .then((res) => res.json())
                        .then((data) => {
                            document.getElementById("asset_tag").value =
                                data.asset_tag;
                        });
                } else {
                    assetTagInput.value = "";
                }
                return;
            }

            if (currentValue) {
                fetch(`/inventory/asset_tag/${currentValue}`)
                    .then((res) => res.json())
                    .then((data) => {
                        document.getElementById("asset_tag").value =
                            data.asset_tag;
                    });
            } else {
                assetTagInput.value = "";
            }
        });

    // WIZARD FUNCTIONS – same as before
    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.toggle("active", i === index);
        });
        updateProgress();
    }

    function validateStep() {
        let valid = true;
        const inputs = steps[currentStep].querySelectorAll("input, select");

        inputs.forEach((input) => {
            const error = input.nextElementSibling;

            if (input.hasAttribute("required") && input.value.trim() === "") {
                input.classList.add("invalid");
                error.style.display = "block";
                valid = false;
            } else {
                input.classList.remove("invalid");
                error.style.display = "none";
            }
        });

        return valid;
    }

    window.nextStep = function () {
        if (!validateStep()) return;

        let next = currentStep + 1;
        const deviceType = document.getElementById("device_type").value;

        if (deviceType === "printer" && next === 1) {
            next = 2;
        }

        if (deviceType === "printer" && next === 3) {
            return;
        }

        if (next < steps.length) {
            currentStep = next;
            showStep(currentStep);
        }
    };

    window.prevStep = function () {
        let prev = currentStep - 1;
        const deviceType = document.getElementById("device_type").value;

        if (deviceType === "printer" && prev === 1) {
            prev = 0;
        }

        if (prev >= 0) {
            currentStep = prev;
            showStep(currentStep);
        }
    };

    const progressBar = document.getElementById("progressBar");
    // const stepLabels = document.querySelectorAll(".step-label"); // pwede nang i-remove kung di na gamit
    const stepIndicators = document.querySelectorAll(".step-indicator-item");

    function updateProgress() {
        const allSteps = Array.from(steps);
        const visibleSteps = allSteps.filter(
            (step) => step.style.display !== "none",
        );

        if (visibleSteps.length === 0) {
            progressBar.style.width = "0%";
            return;
        }

        const currentVisibleIndex = visibleSteps.indexOf(steps[currentStep]);

        if (currentVisibleIndex === -1) {
            progressBar.style.width = "0%";
            return;
        }

        const progress =
            ((currentVisibleIndex + 1) / visibleSteps.length) * 100;
        progressBar.style.width = progress + "%";

        // === NEW: update step indicator circles ===
        if (stepIndicators.length) {
            stepIndicators.forEach((item, i) => {
                // active kung kasalukuyang step
                item.classList.toggle("active", i === currentStep);

                // kung naka-hide yung step sa wizard (e.g. printer case),
                // gawin natin "disabled" look
                if (steps[i] && steps[i].style.display === "none") {
                    item.classList.add("disabled");
                } else {
                    item.classList.remove("disabled");
                }
            });
        }
    }

    document
        .getElementById("inventoryForm")
        ?.addEventListener("submit", function (e) {
            if (!validateStep()) {
                e.preventDefault();
            } else {
                alert("Form submitted!");
            }
        });
});

document.addEventListener("DOMContentLoaded", function () {
    const deviceTypeSelect = document.getElementById("device_type");
    const hardwareTabBtn = document.querySelector(
        ".tab-btn[onclick*='hardware']",
    );
    const softwareTabBtn = document.querySelector(
        ".tab-btn[onclick*='software']",
    );
    const hardwareTabPane = document.getElementById("hardware");
    const basicTabBtn = document.querySelector(".tab-btn[onclick*='basic']");
    const basicTabPane = document.getElementById("basic");

    window.openTab = function (event, tabId) {
        document.querySelectorAll(".tab-content").forEach((tab) => {
            tab.classList.remove("active");
        });

        document.querySelectorAll(".tab-btn").forEach((btn) => {
            btn.classList.remove("active");
        });

        const target = document.getElementById(tabId);
        if (target) {
            target.classList.add("active");
        }

        if (event && event.currentTarget) {
            event.currentTarget.classList.add("active");
        }
    };

    // default active content
    if (basicTabPane) {
        basicTabPane.classList.add("active");
    }

    function updateTabsByDeviceType() {
        const value = deviceTypeSelect ? deviceTypeSelect.value : "";

        if (!hardwareTabBtn || !hardwareTabPane) return;

        if (value === "printer") {
            // HIDE hardware tab button
            hardwareTabBtn.style.display = "none"; // NEW
            softwareTabBtn.style.display = "none"; // NEW

            // Siguraduhin na hindi active ang hardware content
            if (hardwareTabPane.classList.contains("active")) {
                // NEW
                hardwareTabPane.classList.remove("active"); // NEW
                hardwareTabBtn.classList.remove("active"); // NEW

                if (basicTabPane) basicTabPane.classList.add("active"); // NEW
                if (basicTabBtn) basicTabBtn.classList.add("active"); // NEW
            }
            // NOTE: HINDI na ginagalaw ang hardwareTabPane.style.display
            // CSS (.tab-content / .tab-content.active) na bahala.
        } else {
            // SHOW hardware tab button
            hardwareTabBtn.style.display = "inline-block"; // CHANGED: button lang ang ginagalaw
            softwareTabBtn.style.display = "inline-block"; // CHANGED: button lang ang ginagalaw

            // HINDI gagalawin ang .active / style.display ng content.
            // openTab ang magko-control kung alin ang visible.
        }
    }

    if (deviceTypeSelect) {
        deviceTypeSelect.addEventListener("change", updateTabsByDeviceType);
        updateTabsByDeviceType(); // run once on load
    }
});
