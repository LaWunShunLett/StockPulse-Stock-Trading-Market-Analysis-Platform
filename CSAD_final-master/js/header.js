document.addEventListener("DOMContentLoaded", () => {
    const searchButton = document.querySelector("nav .desktop-nav .link-search");
    const closeButton = document.querySelector(".search-container .link-close");
    const desktopNav = document.querySelector(".desktop-nav");
    const searchContainer = document.querySelector(".search-container");
    const overlay = document.querySelector(".overlay");
    const resultBox = document.getElementById("resultBox");
    const searchInput = document.getElementById("searchInput");

    searchButton.addEventListener("click", () => {
        desktopNav.classList.add("hide");
        searchContainer.classList.remove("hide");
        overlay.classList.add("show");
    });

    closeButton.addEventListener("click", () => {
        hideSearch();
    });

    overlay.addEventListener("click", () => {
        hideSearch();
    });

    searchInput.addEventListener("input", () => {
       const input = searchInput.value.toLowerCase();
        resultBox.innerHTML = "";
        if (input) {
            fetch(`search_backend.php?q=${encodeURIComponent(input)}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(suggestion => {
                        const a = document.createElement("a");
                        a.textContent = suggestion.stockShortName + " - " + suggestion.stockLongName;
                        a.href = `stock_page.php?company=${suggestion.stockShortName}`;
                        resultBox.appendChild(a);
                    });
                    resultBox.classList.remove("hide");
                })
                .catch(error => console.error('Error:', error));
        } else {
            resultBox.classList.add("hide");
        }
    });

    function hideSearch() {
        desktopNav.classList.remove("hide");
        searchContainer.classList.add("hide");
        overlay.classList.remove("show");
        resultBox.classList.add("hide");
    }

    // Mobile search functionality
    const menuIconContainer = document.querySelector("nav .menu-icon-container");
    const navContainer = document.querySelector(".nav-container");
    const mobileResultBox = document.getElementById("mobileResultBox");
    const mobileSearchInput = document.getElementById("mobileSearchInput");
    const mobileSearchContainer = document.getElementById("mobileSearchContainer");
    const mobileSearchButton = document.querySelector("nav .mobile-nav .link-search");
    const cancelBtn = document.querySelector(".mobile-search-container .cancel-btn");

    menuIconContainer.addEventListener("click", () => {
        navContainer.classList.toggle("active");
    });

    mobileSearchButton.addEventListener("click", () => {
        mobileSearchContainer.classList.add("show");
        mobileSearchContainer.classList.remove("hide");
    });

    mobileSearchInput.addEventListener("input", () => {
        const input = mobileSearchInput.value.toLowerCase();
        mobileResultBox.innerHTML = "";
        if (input) {
            fetch(`search.php?q=${encodeURIComponent(input)}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(suggestion => {
                        const a = document.createElement("a");
                        a.textContent = suggestion.stockShortName + " - " + suggestion.stockLongName;
                        a.href = `stock_page.php?company=${suggestion.stockShortName}`;
                        mobileResultBox.appendChild(a);
                    });
                    mobileResultBox.classList.remove("hide");
                })
                .catch(error => console.error('Error:', error));
        } else {
            mobileResultBox.classList.add("hide");
        }
    });

    cancelBtn.addEventListener("click", () => {
        mobileSearchContainer.classList.remove("show");
        mobileSearchContainer.classList.add("hide");
        mobileResultBox.classList.add("hide");
    });
});