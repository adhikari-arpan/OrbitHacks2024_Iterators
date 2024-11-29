// Elements
const instructions = document.getElementById('instructions');
const circle = document.getElementById('circle');
const quote = document.getElementById('quote');
const cycleCount = document.getElementById('cycle-count');
const calmMusic = document.getElementById('calm-music');

if (!instructions || !circle || !quote || !cycleCount || !calmMusic) {
    console.error('One or more elements are missing from the DOM.');
}

let musicPlaying = false;
let duration = 8000; // Default duration
let step = 0;
let cycles = 0;

// Breathing steps and motivational quotes
const steps = ['Inhale deeply...', 'Hold your breath...', 'Exhale slowly...', 'Hold your breath...'];
const quotes = [
    "Take a deep breath. You're doing great!",
    "Relax your mind and feel the calm.",
    "Breathe in peace,breathe out stress.",
    "Every breath is a new beginning.",
];


// Update instructions
function updateInstructions() {
    instructions.textContent = steps[step];
    step = (step + 1) % steps.length;

    if (step === 0) {
        cycles++;
        cycleCount.textContent = cycles;
        quote.textContent = quotes[Math.floor(Math.random() * quotes.length)];
    }
}
let holdStartTime = null;
let isHolding = false;

document.getElementById('circle').addEventListener('mousedown', () => {
    holdStartTime = Date.now();
    isHolding = true;
});

document.getElementById('circle').addEventListener('mouseup', () => {
    if (isHolding) {
        const holdDuration = Date.now() - holdStartTime;
        setDuration(8000 + holdDuration); // Adjust the duration
        isHolding = false;
    }
});




// Set theme (Day/Night)
function setTheme(theme) {
    document.body.className = theme === 'day' ? 'day-theme' : 'night-theme';
}

// Toggle music
function toggleMusic() {
    if (!calmMusic) return; // Guard against missing element
    if (musicPlaying) {
        calmMusic.pause();
        musicPlaying = false;
        document.getElementById('music-toggle').textContent = '🎵 Play Music';
    } else {
        calmMusic.play();
        musicPlaying = true;
        document.getElementById('music-toggle').textContent = '🎵 Pause Music';
    }
}

// Sync instructions with animation duration
function syncInstructions() {
    updateInstructions();
    setTimeout(syncInstructions, duration / 4);
}
syncInstructions();

let sessionsCompleted = 0;
let streakCount = 0;
let lastSessionDate = localStorage.getItem('lastSessionDate');
const moodRatingInput = document.getElementById('mood-rating');
const sessionsCountDisplay = document.getElementById('sessions-count');
const streakCountDisplay = document.getElementById('streak-count');
const sessionsFill = document.getElementById('sessions-fill');
const emojis = document.querySelectorAll('.emoji');

function saveProgress() {
    // Increment session count
    sessionsCompleted++;
    sessionsCountDisplay.textContent = sessionsCompleted;
    sessionsFill.style.width = `${(sessionsCompleted % 10) * 10}%`; // For demo purposes, adjust as needed

    // Track streaks
    const currentDate = new Date().toISOString().split('T')[0];
    if (lastSessionDate === currentDate) {
        // Same day, do nothing
    } else if (lastSessionDate === new Date(Date.now() - 86400000).toISOString().split('T')[0]) {
        // Previous day, increase streak
        streakCount++;
    } else {
        // Streak broken, reset
        streakCount = 1;
    }
    streakCountDisplay.textContent = streakCount;
    localStorage.setItem('lastSessionDate', currentDate);

    // Save mood rating and display progress
    const moodRating = moodRatingInput.value;
    localStorage.setItem(`sessionMood-${sessionsCompleted}`, moodRating);
}

// Emoji interaction for mood rating
emojis.forEach((emoji, index) => {
    emoji.addEventListener('click', () => {
        moodRatingInput.value = index + 1; // Set range input to match emoji
    });
});

// On page load, initialize progress tracker
document.addEventListener('DOMContentLoaded', () => {
    sessionsCountDisplay.textContent = localStorage.getItem('sessionsCompleted') || 0;
    streakCountDisplay.textContent = localStorage.getItem('streakCount') || 0;
    sessionsFill.style.width = `${(sessionsCompleted % 10) * 10}%`; // Update progress bar
});