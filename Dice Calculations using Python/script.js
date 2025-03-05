let rollHistory = [];
let cumulativeResults = [];

// Generate inputs for custom die probabilities
function generateCustomDieInputs() {
    let numSides = document.getElementById('numSides').value;
    let customDieContainer = document.getElementById('customDieContainer');
    customDieContainer.innerHTML = ''; // Clear previous inputs
    
    for (let i = 1; i <= numSides; i++) {
        customDieContainer.innerHTML += `
            <label for="side${i}">Side ${i} Probability:</label>
            <input type="number" id="side${i}" value="1" min="0" max="1" step="0.01">
            <br>
        `;
    }
}

// Roll standard dice
function rollDice() {
    let diceType = parseInt(document.getElementById('diceType').value);
    let numDice = parseInt(document.getElementById('numDice').value);
    let numRolls = parseInt(document.getElementById('numRolls').value);

    let rolls = [];
    for (let i = 0; i < numRolls; i++) {
        let total = 0;
        for (let j = 0; j < numDice; j++) {
            total += Math.floor(Math.random() * diceType) + 1;
        }
        rolls.push(total);
    }

    let counts = Array(numDice * diceType).fill(0);
    for (let roll of rolls) {
        counts[roll - numDice]++; // Adjust index to start at 0
    }

    plotDistribution(Array.from({ length: numDice * diceType }, (_, i) => i + numDice), counts);
    displayRollHistory(rolls);

    // Clear custom results
    document.getElementById('customResults').innerHTML = ''; 

    // Update cumulative results
    cumulativeResults = cumulativeResults.concat(rolls);
    updateCumulativeStatistics(cumulativeResults); // Pass cumulative results for statistics

    // Chi-square test
    let expected = Array(numDice * diceType).fill(numRolls / (numDice * diceType));
    let chiSquare = chiSquareTest(expected, counts);
    document.getElementById('standardResults').innerHTML = `<strong>Standard Dice Chi-Square Value:</strong> ${chiSquare.toFixed(2)}<br>`;
}

// Roll custom dice
function rollCustomDice() {
    let numSides = parseInt(document.getElementById('numSides').value);
    let probabilities = [];
    let totalProbability = 0;

    for (let i = 1; i <= numSides; i++) {
        let prob = parseFloat(document.getElementById(`side${i}`).value);
        probabilities.push(prob);
        totalProbability += prob;
    }

    // Check if probabilities sum to 1
    if (Math.abs(totalProbability - 1) > 0.01) {
        alert("Probabilities must sum to 1!");
        return;
    }

    let rolls = [];
    for (let i = 0; i < 1000; i++) { // Simulate 1000 rolls
        let roll = weightedRoll(probabilities);
        rolls.push(roll);
    }

    let counts = Array(numSides).fill(0);
    for (let roll of rolls) {
        counts[roll - 1]++; // Adjust index to start at 0
    }

    plotDistribution(Array.from({ length: numSides }, (_, i) => i + 1), counts);
    displayRollHistory(rolls);

    // Clear standard results
    document.getElementById('standardResults').innerHTML = ''; 

    // Update custom results
    updateCumulativeStatistics(rolls); // Pass custom rolls for statistics
    document.getElementById('customResults').innerHTML = '<strong>Custom Dice Roll Results:</strong><br>' + rolls.join(', ');
}

// Weighted roll for custom dice
function weightedRoll(probabilities) {
    let totalWeight = probabilities.reduce((acc, val) => acc + val, 0);
    let random = Math.random() * totalWeight;
    for (let i = 0; i < probabilities.length; i++) {
        if (random < probabilities[i]) return i + 1;
        random -= probabilities[i];
    }
}

// Display roll history
function displayRollHistory(rolls) {
    rollHistory.push(...rolls);
    let rollHistoryContainer = document.getElementById('rollHistory');
    rollHistoryContainer.innerHTML = 'Roll History: ' + rollHistory.join(', ') + '<br><br>';
}

// Update cumulative statistics
function updateCumulativeStatistics(rolls) {
    // Clear previous results
    let resultsContainer = document.getElementById('standardResults');
    resultsContainer.innerHTML += `<h4>Cumulative Statistics:</h4>`;

    // Calculate new stats
    let allRolls = cumulativeResults.concat(rolls); // Combine all rolls for statistics
    let mean = (calculateMean(allRolls)).toFixed(2);
    let variance = (calculateVariance(allRolls)).toFixed(2);
    let stdDeviation = Math.sqrt(variance).toFixed(2);
    let median = calculateMedian(allRolls).toFixed(2);

    resultsContainer.innerHTML += `
        <strong>Cumulative Mean:</strong> ${mean}<br>
        <strong>Cumulative Variance:</strong> ${variance}<br>
        <strong>Cumulative Standard Deviation:</strong> ${stdDeviation}<br>
        <strong>Cumulative Median:</strong> ${median}
    `;
}

// Calculate mean
function calculateMean(data) {
    return data.reduce((a, b) => a + b) / data.length;
}

// Calculate variance
function calculateVariance(data) {
    let mean = calculateMean(data);
    return data.reduce((sum, val) => sum + (val - mean) ** 2, 0) / data.length;
}

// Calculate median
function calculateMedian(data) {
    let sorted = data.slice().sort((a, b) => a - b);
    let mid = Math.floor(sorted.length / 2);
    return sorted.length % 2 !== 0 ? sorted[mid] : (sorted[mid - 1] + sorted[mid]) / 2;
}

// Chi-square test
function chiSquareTest(expected, observed) {
    let chiSquare = 0;
    for (let i = 0; i < expected.length; i++) {
        chiSquare += Math.pow(observed[i] - expected[i], 2) / expected[i];
    }
    return chiSquare;
}

// Compare two dice types
function compareDice() {
    let diceType1 = document.getElementById('diceType1').value;
    let diceType2 = document.getElementById('diceType2').value;

    let results1 = rollDiceInternal(diceType1, 1000);
    let results2 = rollDiceInternal(diceType2, 1000);

    // Plot comparison chart
    plotComparison(results1, results2);
}

function rollDiceInternal(diceType, numRolls) {
    let rolls = [];
    for (let i = 0; i < numRolls; i++) {
        let total = Math.floor(Math.random() * diceType) + 1;
        rolls.push(total);
    }
    return rolls;
}

// Plot distribution
function plotDistribution(outcomes, counts) {
    let chartColor = document.getElementById('chartColor').value;

    let ctx = document.getElementById('diceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: outcomes,
            datasets: [{
                label: 'Frequency of Dice Rolls',
                data: counts,
                backgroundColor: chartColor,
                borderColor: chartColor,
                borderWidth: 1
            }]
        }
    });
}

// Plot comparison
function plotComparison(results1, results2) {
    let ctx = document.getElementById('comparisonChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array.from({ length: results1.length }, (_, i) => i + 1),
            datasets: [
                {
                    label: 'Dice Type 1',
                    data: results1,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)'
                },
                {
                    label: 'Dice Type 2',
                    data: results2,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)'
                }
            ]
        }
    });
}

// Download results
function downloadResults() {
    let data = JSON.stringify(cumulativeResults, null, 2);
    let blob = new Blob([data], { type: 'application/json' });
    let url = URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url;
    a.download = 'dice_results.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
