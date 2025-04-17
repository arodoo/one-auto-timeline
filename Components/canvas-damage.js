class DamageCanvas {
    constructor(inputId, vehicleId) {
        this.elements = {
            canvas: document.getElementById(`damage-canvas-${vehicleId}`),
            img: document.getElementById(`vehicle-${vehicleId}-img`),
            hiddenInput: document.getElementById(inputId)
        };
        this.vehicleId = vehicleId;
        this.inputId = inputId;
        this.init();
    }

    init() {
        if (!this.validateElements()) return;
        this.ctx = this.elements.canvas.getContext('2d');
        this.tempCanvas = this.createTempCanvas();
        this.tempCtx = this.tempCanvas.getContext('2d');
        this.setupEvents();
        this.setupImageLoad();
    }

    validateElements() {
        if (!this.elements.canvas || !this.elements.img || !this.elements.hiddenInput) {
            console.error('Required elements not found:', this.elements);
            return false;
        }
        return true;
    }

    createTempCanvas() {
        const canvas = document.createElement('canvas');
        canvas.style.display = 'none';
        return canvas;
    }

    setupEvents() {
        this.elements.canvas.addEventListener('click', (e) => {
            const coordinates = this.calculateCoordinates(e);
            this.drawAndSaveImage(coordinates);
        });
    }

    calculateCoordinates(event) {
        const rect = this.elements.canvas.getBoundingClientRect();
        const scaleX = this.elements.img.naturalWidth / rect.width;
        const scaleY = this.elements.img.naturalHeight / rect.height;
        return {
            x: (event.clientX - rect.left) * scaleX,
            y: (event.clientY - rect.top) * scaleY
        };
    }

    drawAndSaveImage(coords) {
        this.clearCanvases();
        this.tempCtx.drawImage(this.elements.img, 0, 0, this.elements.img.naturalWidth, this.elements.img.naturalHeight);
        this.drawArrow(coords.x, coords.y);
        this.ctx.drawImage(this.tempCanvas, 0, 0);
        this.saveState(coords);
    }

    clearCanvases() {
        this.ctx.clearRect(0, 0, this.elements.canvas.width, this.elements.canvas.height);
        this.tempCtx.clearRect(0, 0, this.tempCanvas.width, this.tempCanvas.height);
    }

    drawArrow(x, y) {
        const arrowLength = 30;
        const headLength = 15;
        const headWidth = 10;
        const stemWidth = 4;
        
        this.tempCtx.save();
        this.tempCtx.translate(x, y);
        this.tempCtx.rotate(-Math.PI / 2);
        
        this.tempCtx.fillStyle = 'red';
        this.tempCtx.strokeStyle = 'red';
        this.tempCtx.lineWidth = 1;
        
        this.tempCtx.beginPath();
        this.tempCtx.moveTo(0, 0);
        this.tempCtx.lineTo(-headWidth/2, headLength);
        this.tempCtx.lineTo(headWidth/2, headLength);
        this.tempCtx.closePath();
        this.tempCtx.fill();
        
        this.tempCtx.fillRect(-stemWidth/2, headLength, stemWidth, arrowLength - headLength);
        this.tempCtx.restore();
    }

    saveState(coords) {
        const base64Image = this.tempCanvas.toDataURL('image/png');
        this.elements.hiddenInput.value = base64Image;
        
        // Fixed: Only store the base64 image value
        localStorage.setItem(this.inputId, JSON.stringify({
            value: base64Image
        }));
        
        localStorage.setItem(`damage-point-${this.vehicleId}`, JSON.stringify({
            x: coords.x,
            y: coords.y
        }));
    }

    setupImageLoad() {
        const setupCanvas = () => {
            this.elements.canvas.width = this.elements.img.naturalWidth;
            this.elements.canvas.height = this.elements.img.naturalHeight;
            this.tempCanvas.width = this.elements.img.naturalWidth;
            this.tempCanvas.height = this.elements.img.naturalHeight;
            
            const savedData = localStorage.getItem(`damage-point-${this.vehicleId}`);
            if (savedData) {
                const data = JSON.parse(savedData);
                this.drawAndSaveImage(data);
            }
        };

        if (this.elements.img.complete) {
            setupCanvas();
        } else {
            this.elements.img.onload = setupCanvas;
        }
    }

    disable() {
        if (this.elements.canvas) {
            this.elements.canvas.style.pointerEvents = 'none';
            this.elements.canvas.style.opacity = '0.5';
        }
    }

    enable() {
        if (this.elements.canvas) {
            this.elements.canvas.style.pointerEvents = 'auto';
            this.elements.canvas.style.opacity = '1';
        }
    }
}

// Global initialization function
window.initDamageCanvas = function(inputId, vehicleId) {
    const instance = new DamageCanvas(inputId, vehicleId);
    // Store instance in global object for later access
    window.damageCanvasInstances = window.damageCanvasInstances || {};
    window.damageCanvasInstances[vehicleId] = instance;
    return instance;
};