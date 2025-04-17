<?php

class Canvas {
    private $id;
    private $title;
    private $description;
    private $key;
    private $color; // Optional color property
    private $dbName;

    public function __construct($id, $title, $description, $key, $color = '', $dbName = '') {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->key = $key;
        $this->color = $color; // Initialize color
        $this->dbName = $dbName;
    }

    public function render() {
        return '<h1 style="' . $this->color . '">' . $this->title . '</h1>
                <p>' . $this->description . '</p>
                <button id="clear-' . $this->id . '" class="btn btn-danger">Effacer</button>
                <button id="delete-' . $this->id . '" class="btn btn-danger" style="display: none;">Supprimer</button>
                <div class="canvas-container">
                    <canvas id="' . $this->id . '" data-db-name="' . $this->dbName . '" style="border:1px solid #000000; display: block; margin: 0 auto;"></canvas>
                </div>
                <br>
                <button id="saveToLocal-' . $this->id . '" class="btn">Enregistrer</button>
                <style>
                    .canvas-container {
                        width: 100%;
                        max-width: 800px;
                        margin: 0 auto;
                        position: relative;
                    }

                    #' . $this->id . ' {
                        width: 100%;
                        height: auto;
                    }

                    @media (max-width: 768px) {
                        .canvas-container {
                            width: 100%;
                            max-width: 100%;
                        }

                        #' . $this->id . ' {
                            width: 100%;
                            height: auto;
                        }
                    }
                </style>';
    }

    public function initializeCanvas() {
        echo '<script>
            (function () {
                const canvasId = "' . $this->id . '";
                const canvas = document.getElementById(canvasId);
                const ctx = canvas.getContext("2d");
                let drawing = false;
                let lastX = 0;
                let lastY = 0;
                let strokes = [];
                let currentStroke = [];
                let isLocked = false;

                function resizeCanvas() {
                    const container = canvas.parentElement;
                    const savedSize = localStorage.getItem(`meta-${canvasId}-size`);
                    if (savedSize) {
                        const size = JSON.parse(savedSize);
                        canvas.width = size.width;
                        canvas.height = size.height;
                    } else {
                        canvas.width = container.clientWidth;
                        canvas.height = container.clientWidth * 0.75;
                    }
                    redrawCanvas();
                }

                function drawGrid() {
                    const gridSize = 80;
                    ctx.strokeStyle = "#e0e0e0";
                    ctx.lineWidth = 0.5;
                    for (let x = 0; x <= canvas.width; x += gridSize) {
                        ctx.beginPath();
                        ctx.moveTo(x, 0);
                        ctx.lineTo(x, canvas.height);
                        ctx.stroke();
                    }
                    for (let y = 0; y <= canvas.height; y += gridSize) {
                        ctx.beginPath();
                        ctx.moveTo(0, y);
                        ctx.lineTo(canvas.width, y);
                        ctx.stroke();
                    }
                }

                function draw(event) {
                    if (!drawing || isLocked) return;
                    ctx.lineWidth = 2;
                    ctx.lineCap = "round";
                    ctx.strokeStyle = "black";

                    ctx.beginPath();
                    ctx.moveTo(lastX, lastY);
                    ctx.lineTo(event.offsetX, event.offsetY);
                    ctx.stroke();
                    [lastX, lastY] = [event.offsetX, event.offsetY];
                    currentStroke.push([lastX, lastY]);
                }

                function redrawCanvas() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    drawGrid();
                    strokes.forEach(stroke => {
                        ctx.beginPath();
                        ctx.moveTo(stroke[0][0], stroke[0][1]);
                        stroke.forEach(([x, y]) => {
                            ctx.lineTo(x, y);
                            ctx.stroke();
                        });
                    });
                    const savedSketch = localStorage.getItem(canvasId);
                    if (savedSketch) {
                        const data = JSON.parse(savedSketch);
                        const img = new Image();
                        img.src = data.value;
                        img.onload = () => {
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        };
                    }
                }

                function clearCanvas() {
                    strokes = [];
                    localStorage.removeItem(`meta-${canvasId}-strokes`);
                    redrawCanvas();
                    unlockCanvas();
                }

                function deleteSketch() {
                    strokes = [];
                    localStorage.removeItem(canvasId);
                    localStorage.removeItem(`meta-${canvasId}-strokes`);
                    localStorage.removeItem(`meta-${canvasId}-size`);
                    redrawCanvas();
                    document.getElementById(`saveToLocal-${canvasId}`).disabled = false;
                    document.getElementById(`clear-${canvasId}`).style.display = "inline-block";
                    document.getElementById(`delete-${canvasId}`).style.display = "none";
                    unlockCanvas();
                }

                function saveToLocal() {
                    const dataURL = canvas.toDataURL("image/png");
                    const data = { id: canvasId, key: "' . $this->key . '", value: dataURL };
                    localStorage.setItem(canvasId, JSON.stringify(data));
                    localStorage.setItem(`meta-${canvasId}-strokes`, JSON.stringify(strokes));
                    localStorage.setItem(`meta-${canvasId}-size`, JSON.stringify({ width: canvas.width, height: canvas.height }));
                    alert("Croquis enregistré !");
                    document.getElementById(`saveToLocal-${canvasId}`).disabled = true;
                    document.getElementById(`clear-${canvasId}`).style.display = "none";
                    document.getElementById(`delete-${canvasId}`).style.display = "inline-block";
                    lockCanvas();
                }

                function lockCanvas() {
                    isLocked = true;
                    canvas.style.pointerEvents = "none";
                }

                function unlockCanvas() {
                    isLocked = false;
                    canvas.style.pointerEvents = "auto";
                }

                canvas.addEventListener("mousedown", (event) => {
                    if (isLocked) return;
                    drawing = true;
                    currentStroke = [];
                    [lastX, lastY] = [event.offsetX, event.offsetY];
                });

                canvas.addEventListener("mouseup", () => {
                    if (isLocked) return;
                    drawing = false;
                    if (currentStroke.length) {
                        strokes.push([...currentStroke]);
                    }
                });

                canvas.addEventListener("mouseout", () => drawing = false);
                canvas.addEventListener("mousemove", draw);

                window.addEventListener("resize", resizeCanvas);
                resizeCanvas();

                // Initial draw
                drawGrid();

                // Load saved strokes from local storage
                const savedStrokes = localStorage.getItem(`meta-${canvasId}-strokes`);
                if (savedStrokes) {
                    strokes = JSON.parse(savedStrokes);
                    resizeCanvas(); // Ensure canvas is resized before redrawing strokes and image
                }

                // Check if sketch is saved to local storage
                const savedSketch = localStorage.getItem(canvasId);
                if (savedSketch) {
                    document.getElementById(`saveToLocal-${canvasId}`).disabled = true;
                    document.getElementById(`clear-${canvasId}`).style.display = "none";
                    document.getElementById(`delete-${canvasId}`).style.display = "inline-block";
                    lockCanvas();
                }

                document.getElementById(`clear-${canvasId}`).addEventListener("click", clearCanvas);
                document.getElementById(`delete-${canvasId}`).addEventListener("click", deleteSketch);
                document.getElementById(`saveToLocal-${canvasId}`).addEventListener("click", saveToLocal);
            })();
        </script>';
    }

    public function loadCanvasData() {
        echo '<script>
            (function () {
                const canvasId = "' . $this->id . '";
                const savedStrokes = localStorage.getItem(`meta-${canvasId}-strokes`);
                if (savedStrokes) {
                    const strokes = JSON.parse(savedStrokes);
                    const canvas = document.getElementById(canvasId);
                    const ctx = canvas.getContext("2d");
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    strokes.forEach(stroke => {
                        ctx.beginPath();
                        ctx.moveTo(stroke[0][0], stroke[0][1]);
                        stroke.forEach(([x, y]) => {
                            ctx.lineTo(x, y);
                            ctx.stroke();
                        });
                    });
                }

                const savedSketch = localStorage.getItem(canvasId);
                if (savedSketch) {
                    const data = JSON.parse(savedSketch);
                    const img = new Image();
                    img.src = data.value;
                    img.onload = () => {
                        const canvas = document.getElementById(canvasId);
                        const ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    };
                }
            })();
        </script>';
    }
}
?>