document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById("logoCanvas");
    if (!canvas) return;

    const ctx = canvas.getContext("2d");

    ctx.fillStyle = "#e8f3f4";
    ctx.fillRect(0, 0, 56, 56);

    ctx.fillStyle = "#8c4a12";
    const croquetas = [
        { x: 21, y: 27, r: 4.5 },
        { x: 35, y: 27, r: 4.5 },
        { x: 28, y: 24, r: 5 }
    ];

    croquetas.forEach(c => {
        ctx.beginPath();
        ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2);
        ctx.fill();
    });

    ctx.fillStyle = "#e5841b";
    ctx.beginPath();
    ctx.moveTo(13, 28);
    ctx.lineTo(43, 28);
    ctx.lineTo(49, 44);
    ctx.lineTo(7, 44);
    ctx.closePath();
    ctx.fill();

    ctx.fillStyle = "#c96f10";
    ctx.fillRect(12, 27, 32, 3.5);

    ctx.fillStyle = "#1d5c63";
    ctx.fillRect(6, 44, 44, 3);

    ctx.strokeStyle = "#ffffff";
    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.beginPath();
    ctx.moveTo(15, 33);
    ctx.lineTo(13, 40);
    ctx.stroke();
});