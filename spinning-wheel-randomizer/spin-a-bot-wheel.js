document.addEventListener("DOMContentLoaded", function () {
    const wheel = document.getElementById("spin-a-bot-wheel");
    const resultText = document.getElementById("spin-result");

    // Only proceed if both elements exist
    if (!wheel || !resultText) return;

    let spinning = false;

    const botLinks = [
        { name: "Cookie", url: "/recipe-ai/" },
        { name: "Wayne", url: "/ai-made-ai/" },
        { name: "Joss", url: "/nightmare-dream-ai/" },
        { name: "Fury", url: "/supernatural-ai/" },
        { name: "The Devil", url: "/ai-devil/" },
        { name: "Help Bot", url: "/help-bot/" },
        { name: "Drusilla", url: "/real-witch-ai/" }
    ];

    wheel.addEventListener("click", function () {
        if (spinning) return;
        spinning = true;
        resultText.innerText = "Spinning... Who will you get?";

        let randomIndex = Math.floor(Math.random() * botLinks.length);
        let degrees = 7200 + (randomIndex * (360 / botLinks.length));

        wheel.style.transition = "transform 5s ease-out";
        wheel.style.transform = `rotate(${degrees}deg)`;

        setTimeout(() => {
            wheel.style.transition = "none";
            let finalRotation = (randomIndex * (360 / botLinks.length)) % 360;
            wheel.style.transform = `rotate(${finalRotation}deg)`;

            resultText.innerText = `You got: ${botLinks[randomIndex].name}! Redirecting...`;

            setTimeout(() => {
                window.location.href = botLinks[randomIndex].url;
            }, 2000);
        }, 5000);
    });
});
