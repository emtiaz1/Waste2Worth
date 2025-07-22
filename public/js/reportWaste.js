// reportWaste.js
const form = document.getElementById("wasteForm");
const recentReports = document.getElementById("recentReports");
const totalWasteDisplay = document.getElementById("totalWaste");
const mostTypeDisplay = document.getElementById("mostType");
const cleanupBar = document.getElementById("cleanupBar");
const cleanupText = document.getElementById("cleanupText");

let totalWaste = 0;
let wasteTypes = {};
let cleanedPercent = 0;
let reports = [];

function loadData() {
    const savedWaste = localStorage.getItem("totalWaste");
    const savedTypes = localStorage.getItem("wasteTypes");
    const savedReports = localStorage.getItem("reports");

    if (savedWaste) totalWaste = parseFloat(savedWaste);
    if (savedTypes) wasteTypes = JSON.parse(savedTypes);
    if (savedReports) reports = JSON.parse(savedReports);

    updateUI();
}

function saveData() {
    localStorage.setItem("totalWaste", totalWaste);
    localStorage.setItem("wasteTypes", JSON.stringify(wasteTypes));
    localStorage.setItem("reports", JSON.stringify(reports));
}

function updateUI() {
    totalWasteDisplay.textContent = `${totalWaste.toFixed(2)} kg`;
    const mostType = Object.entries(wasteTypes).sort((a, b) => b[1] - a[1])[0];
    mostTypeDisplay.textContent = mostType ? mostType[0] : "None";

    cleanedPercent = Math.min(100, (totalWaste * 0.7));
    cleanupBar.style.width = `${cleanedPercent}%`;
    cleanupText.textContent = `${Math.round(cleanedPercent)}% of reported waste cleaned`;

    recentReports.innerHTML = '';
    for (let r of [...reports].reverse()) {
        const reportHTML = `
        <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <h4 style="font-weight: bold;">${r.type} Waste at ${r.location}</h4>
                <span style="background-color: #fef3c7; color: #92400e; font-size: 12px; padding: 2px 6px; border-radius: 4px;">Pending</span>
            </div>
            <p style="color: #555;">${r.description}</p>
            ${r.imageURL ? `<img src="${r.imageURL}" style="width: 100%; height: 160px; object-fit: cover; border-radius: 6px;">` : ''}
            <p style="font-size: 12px; color: #888;">Reported earlier</p>
        </div>`;
        recentReports.insertAdjacentHTML("beforeend", reportHTML);
    }
}

form.addEventListener("submit", (e) => {
    e.preventDefault();

    const type = document.getElementById("wasteType").value;
    const amount = parseFloat(document.getElementById("wasteAmount").value) || 0;
    const unit = document.getElementById("wasteUnit").value;
    const location = document.getElementById("wasteLocation").value;
    const description = document.getElementById("wasteDescription").value;
    const imageFile = document.getElementById("wasteImage").files[0];

    totalWaste += amount;
    wasteTypes[type] = (wasteTypes[type] || 0) + 1;

    let imageURL = "";
    if (imageFile) {
        imageURL = URL.createObjectURL(imageFile);
    }

    const newReport = { type, amount, unit, location, description, imageURL };
    reports.unshift(newReport);

    saveData();
    updateUI();

    form.reset();
});

loadData();
