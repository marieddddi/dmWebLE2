const tableSizeInput = document.getElementById("table-size");
const createTableButton = document.getElementById("create-table");
const pixelTable = document.getElementById("pixel-table");
const colorPalette = document.getElementById("color-palette");
const uploadImageButton = document.getElementById("upload-image");
const exportSmallButton = document.getElementById("export-small");
const exportMediumButton = document.getElementById("export-medium");
const exportLargeButton = document.getElementById("export-large");
const producedSmileys = document.getElementById("produced-smileys");

const colors = ["#000000", "#ffffff", "#ff0000", "#00ff00", "#0000ff"];

function createPixelTable(size) {
    pixelTable.innerHTML = "";
    for (let i = 0; i < size; i++) {
        const row = document.createElement("tr");
        for (let j = 0; j < size; j++) {
            const cell = document.createElement("td");
            cell.addEventListener("click", changePixelColor);
            row.appendChild(cell);
        }
        pixelTable.appendChild(row);
    }
}

function changePixelColor(event) {
    const cell = event.target;
    const color = colorPalette.dataset.selectedColor || "#000000";
    cell.style.backgroundColor = color;
}

function createColorPalette() {
    colors.forEach(color => {
        const div = document.createElement("div");
        div.style.backgroundColor = color;
        div.addEventListener("click", selectColor);
        colorPalette.appendChild(div);
    });
}

function selectColor(event) {
    const div = event.target;
    const color = div.style.backgroundColor;
    colorPalette.dataset.selectedColor = color;
    colorPalette.querySelectorAll("div").forEach(element => {
        element.classList.remove("selected");
    });
    div.classList.add("selected");
}

// à terminer
function uploadImage() {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = "image/*";
    input.addEventListener("change", handleImageUpload);
    input.click();
}

// à terminer
function handleImageUpload(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
        const img = document.createElement("img");
        img.src = reader.result;
        pixelTable.innerHTML = "";
        pixelTable.appendChild(img);
    };
}

function downloadPng(dataUrl) {
    const link = document.createElement("a");
    link.href = dataUrl;
    link.download = "image.png";
    link.click();
}

function exportPixelTableToPng(sizepng) {
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    const size = pixelTable.rows.length;
    const cellSize = 10; // Taille de chaque pixel

    let facteur = 1;

    if (sizepng == "small") {
        facteur = 0.5;
    }
    if (sizepng == "large") {
        facteur = 2;
    }
    
    canvas.width = size * cellSize * facteur;
    canvas.height = size * cellSize * facteur;

    for (let i = 0; i < size; i++) {
        for (let j = 0; j < size; j++) {
            const cell = pixelTable.rows[i].cells[j];
            const color = cell.style.backgroundColor || "#ffffff"; // Couleur par défaut si aucune n'est définie

            ctx.fillStyle = color;
            ctx.fillRect(j * cellSize * facteur, i * cellSize * facteur, cellSize * facteur, cellSize * facteur);
        }
    }

    const png = canvas.toDataURL("image/png");
    convertPngToJson(png);
    reloadProducedSmileys();
    //downloadPng(png);
}


function convertPngToJson(png) {
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    const img = new Image();
    img.src = png;

    img.onload = () => {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const pixels = imageData.data;
        const colors = [];

        const taille = Math.sqrt(canvas.width); 

        for (let i = 0; i < pixels.length; i += 4) {
            const r = pixels[i];
            const g = pixels[i + 1];
            const b = pixels[i + 2];
            const color = rgbToHex(r, g, b);
            colors.push(color);
        }

        const json = {
            taille: taille,
            chaine: colors.join(";")
        };

        console.log(json);
    };

}

function rgbToHex(r, g, b) {
    const componentToHex = (c) => {
        const hex = c.toString(16);
        return hex.length === 1 ? "0" + hex : hex;
    };

    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

// à terminer
function reloadProducedSmileys() {
    const smileys = JSON.parse(localStorage.getItem("smileys")) || [];
    producedSmileys.innerHTML = "";
    smileys.forEach(smiley => {
        const img = document.createElement("img");
        img.src = smiley;
        img.addEventListener("click", () => {
            const newImg = document.createElement("img");
            newImg.src = smiley;
            pixelTable.innerHTML = "";
            pixelTable.appendChild(newImg);
        });
        producedSmileys.appendChild(img);
    });
}

// à terminer
function duplicateProducedSmiley() {
    const img = pixelTable.querySelector("img");
    if (img) {
        const newImg = img.cloneNode(true);
        pixelTable.innerHTML = "";
        pixelTable.appendChild(newImg);
    }
}

createTableButton.addEventListener("click", () => {
    const size = tableSizeInput.value;
    createPixelTable(size);
});

window.addEventListener("load", () => {
    createColorPalette();
    createPixelTable(10);
    reloadProducedSmileys();
});

exportSmallButton.addEventListener("click", () => {
    exportPixelTableToPng("small");
});

exportMediumButton.addEventListener("click", () => {
    exportPixelTableToPng("medium");
});

exportLargeButton.addEventListener("click", () => {
    exportPixelTableToPng("large");
});

producedSmileys.addEventListener("click", duplicateProducedSmiley);
