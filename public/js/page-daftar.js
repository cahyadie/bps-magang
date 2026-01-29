document.addEventListener("DOMContentLoaded", function () {
    
    // --- 1. HANDLE ALERT AUTO HIDE ---
    const successAlert = document.querySelector(".success-alert");
    if (successAlert) {
        setTimeout(() => {
            // Add CSS class for fade-out animation (ensure this class exists in your CSS)
            successAlert.style.transition = "opacity 0.5s ease";
            successAlert.style.opacity = "0";
            
            // Remove ONLY the alert element after animation
            setTimeout(() => {
                successAlert.remove(); 
            }, 500);
        }, 3000);
    }

    // --- 2. AJAX LOGIC USING DOMPARSER (NO PARTIALS REQUIRED) ---
    const searchInput = document.getElementById("searchInput");
    const kampusSelect = document.getElementById("kampusSelect");
    const yearSelect = document.getElementById("yearSelect");
    const filterForm = document.getElementById("filterForm");
    const dataContainer = document.getElementById("dataContainer");

    // Loaders
    const searchLoader = document.getElementById("searchLoader");
    const kampusLoader = document.getElementById("kampusLoader");
    const yearLoader = document.getElementById("yearLoader");

    // Only proceed if critical elements exist
    if (!filterForm || !dataContainer) return;

    // Main Fetch Function
    function fetchData(triggeredBy) {
        // 1. Show Specific Loader
        if (triggeredBy === "search" && searchLoader) searchLoader.style.display = "block";
        if (triggeredBy === "kampus" && kampusLoader) kampusLoader.style.display = "block";
        if (triggeredBy === "year" && yearLoader) yearLoader.style.display = "block";

        let url;

        // Check if trigger is Pagination (Link) or Filter (Form)
        if (typeof triggeredBy === "object" && triggeredBy.tagName === "A") {
            url = triggeredBy.href;
            dataContainer.style.opacity = "0.5"; // Visual loading effect
        } else {
            // Construct URL from form action and inputs
            const baseUrl = new URL(filterForm.action);
            const params = new URLSearchParams(new FormData(filterForm));
            url = `${baseUrl}?${params.toString()}`;
            
            // Optional: visual opacity for filter updates too
            dataContainer.style.opacity = "0.5"; 
        }

        // 2. Request AJAX
        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
        .then((response) => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then((html) => {
            // 3. DOM PARSER TECHNIQUE
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            // Extract only #dataContainer content from the response
            const newContent = doc.getElementById("dataContainer");
            
            if (newContent) {
                // Update page content
                dataContainer.innerHTML = newContent.innerHTML;
                dataContainer.style.opacity = "1";

                // 4. Update Browser URL (Push State)
                if (typeof triggeredBy !== "object") {
                    const currentUrl = new URL(window.location);
                    const formData = new FormData(filterForm);
                    
                    // Clear existing params first to avoid duplicates if needed, 
                    // or just overwrite them as below:
                    for (const [key, value] of formData) {
                        if (value && value !== 'all') {
                            currentUrl.searchParams.set(key, value);
                        } else {
                            currentUrl.searchParams.delete(key);
                        }
                    }
                    window.history.pushState({}, "", currentUrl);
                } else {
                    window.history.pushState({}, "", url);
                }
            } else {
                console.error("Error: #dataContainer not found in response.");
            }
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
            dataContainer.style.opacity = "1"; // Restore opacity on error
        })
        .finally(() => {
            // 5. Hide All Loaders
            if (searchLoader) searchLoader.style.display = "none";
            if (kampusLoader) kampusLoader.style.display = "none";
            if (yearLoader) yearLoader.style.display = "none";
        });
    }

    // --- Event Listeners ---

    // 1. Live Search with Debounce
    let debounceTimer;
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchData("search");
            }, 500); // 500ms delay is usually better for UX
        });
    }

    // 2. Dropdown Changes
    if (kampusSelect) {
        kampusSelect.addEventListener("change", () => fetchData("kampus"));
    }
    if (yearSelect) {
        yearSelect.addEventListener("change", () => fetchData("year"));
    }

    // 3. Reset Button (Optional, if you have a reset button)
    const resetBtn = document.getElementById("resetBtn"); // Ensure your reset button has this ID
    if (resetBtn) {
        resetBtn.addEventListener("click", function(e) {
            e.preventDefault();
            // Reset form values
            if(searchInput) searchInput.value = "";
            if(kampusSelect) kampusSelect.value = "";
            if(yearSelect) yearSelect.value = "all";
            fetchData("reset");
        });
    }

    // 4. Pagination Click Intercept (Delegation)
    dataContainer.addEventListener("click", function (e) {
        // Target pagination links inside Laravel's pagination structure
        const paginationLink = e.target.closest(".pagination a") || e.target.closest("nav[role='navigation'] a");
        
        if (paginationLink) {
            e.preventDefault();
            fetchData(paginationLink);
        }
    });
});