let rollHistory = [];

function rollDice() {
    // Start animation
    const dice = document.getElementById("dice");
    dice.style.animation = "roll 0.5s";
    
    // Simulate rolling the dice
    setTimeout(() => {
        dice.style.animation = ""; // Reset animation
        const result = Math.floor(Math.random() * 6) + 1; // Roll a 6-sided dice
        rollHistory.push(result);
        updateResult(result);
        updateStatistics(rollHistory);
    }, 500);
}

function updateResult(result) {
    document.getElementById("result").innerText = `You rolled: ${result}`;
    document.getElementById("rollHistory").innerText = `Roll History: ${rollHistory.join(', ')}`;
}

function updateStatistics(rolls) {
    const mean = calculateMean(rolls);
    const median = calculateMedian(rolls);
    const variance = calculateVariance(rolls);
    const stdDeviation = Math.sqrt(variance).toFixed(2);

    document.getElementById("mean").innerText = mean.toFixed(2);
    document.getElementById("median").innerText = median.toFixed(2);
    document.getElementById("variance").innerText = variance.toFixed(2);
    document.getElementById("stdDeviation").innerText = stdDeviation;
}

function calculateMean(rolls) {
    const sum = rolls.reduce((a, b) => a + b, 0);
    return sum / rolls.length;
}

function calculateMedian(rolls) {
    rolls.sort((a, b) => a - b);
    const mid = Math.floor(rolls.length / 2);
    return rolls.length % 2 !== 0 ? rolls[mid] : (rolls[mid - 1] + rolls[mid]) / 2;
}

function calculateVariance(rolls) {
    const mean = calculateMean(rolls);
    return rolls.reduce((acc, val) => acc + Math.pow(val - mean, 2), 0) / rolls.length;
}
