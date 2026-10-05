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

            const selectedOption = this.options[this.selectedIndex];

            const deviceConfig = {
                hasBasic: selectedOption.dataset.hasBasic === "1",
                hasPurchase: selectedOption.dataset.hasPurchase === "1",
                hasHardware: selectedOption.dataset.hasHardware === "1",
                hasLicenseNotes: selectedOption.dataset.hasLicenseNotes === "1",
            };

            console.log(deviceConfig);
            if (steps.length) {
                const hardwareStep = steps[1];
                const purchaseStep = steps[2];
                const licenseStep = steps[3];

                if (!deviceConfig.hasHardware) {
                    hardwareStep.style.display = "none";
                } else {
                    hardwareStep.style.display = "";
                }

                if (!deviceConfig.hasPurchase) {
                    purchaseStep.style.display = "none";
                } else {
                    purchaseStep.style.display = "";
                }

                if (!deviceConfig.hasLicenseNotes) {
                    licenseStep.style.display = "none";
                } else {
                    licenseStep.style.display = "";
                }
                updateSubmitButton();
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

    function updateSubmitButton() {
        const nextButton = document.getElementById("next_step_3");
        const submitButton = document.getElementById("submit_step_3");

        if (!nextButton || !submitButton) return;

        let next = currentStep + 1;

        // Skip hidden steps
        while (next < steps.length && steps[next].style.display === "none") {
            next++;
        }

        // Wala nang next visible step
        if (next >= steps.length) {
            nextButton.style.display = "none";
            submitButton.style.display = "inline-block";
        } else {
            nextButton.style.display = "inline-block";
            submitButton.style.display = "none";
        }
    }

    window.nextStep = function () {
        if (!validateStep()) return;

        let next = currentStep + 1;

        // Skip hidden steps
        while (next < steps.length && steps[next].style.display === "none") {
            next++;
        }

        if (next < steps.length) {
            currentStep = next;
            showStep(currentStep);
        }

        updateSubmitButton();
    };

    window.prevStep = function () {
        let prev = currentStep - 1;

        // Skip hidden steps
        while (prev >= 0 && steps[prev].style.display === "none") {
            prev--;
        }

        if (prev >= 0) {
            currentStep = prev;
            showStep(currentStep);
        }

        updateSubmitButton();
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
    const purchaseTabBtn = document.querySelector(
        ".tab-btn[onclick*='purchase']",
    );

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
        if (!deviceTypeSelect) return;

        const selectedOption =
            deviceTypeSelect.options[deviceTypeSelect.selectedIndex];

        const deviceConfig = {
            hasHardware: selectedOption.dataset.hasHardware === "1",
            hasPurchase: selectedOption.dataset.hasPurchase === "1",
            hasLicenseNotes: selectedOption.dataset.hasLicenseNotes === "1",
        };

        // HARDWARE
        if (hardwareTabBtn) {
            hardwareTabBtn.style.display = deviceConfig.hasHardware
                ? "inline-block"
                : "none";
        }

        // PURCHASE
        if (purchaseTabBtn) {
            purchaseTabBtn.style.display = deviceConfig.hasPurchase
                ? "inline-block"
                : "none";
        }

        // SOFTWARE / LICENSE
        if (softwareTabBtn) {
            softwareTabBtn.style.display = deviceConfig.hasLicenseNotes
                ? "inline-block"
                : "none";
        }

        // Kung currently active yung tab na tinago,
        // balik tayo sa Basic Info.
        const activeTab = document.querySelector(".tab-content.active");

        if (
            activeTab &&
            ((activeTab.id === "hardware" && !deviceConfig.hasHardware) ||
                (activeTab.id === "purchase" && !deviceConfig.hasPurchase) ||
                (activeTab.id === "software" && !deviceConfig.hasLicenseNotes))
        ) {
            document.querySelectorAll(".tab-content").forEach((tab) => {
                tab.classList.remove("active");
            });

            document.querySelectorAll(".tab-btn").forEach((btn) => {
                btn.classList.remove("active");
            });

            if (basicTabPane) {
                basicTabPane.classList.add("active");
            }

            if (basicTabBtn) {
                basicTabBtn.classList.add("active");
            }
        }
    }

    if (deviceTypeSelect) {
        deviceTypeSelect.addEventListener("change", updateTabsByDeviceType);
        updateTabsByDeviceType(); // run once on load
    }
});
