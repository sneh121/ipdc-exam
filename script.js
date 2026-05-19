let questions = [];
let currentIndex = 0;
let score = 0; // This will store the earned marks (out of 70)
let answered = false;
let currentSection = '';
let showingIntro = false;

const sectionDetails = {
    'A': {
        title: 'Section A: Morality & Character Development',
        desc: 'This section contains True or False questions. Analyze each statement and decide if it represents ethical behavior and dynamic character growth.',
        count: 10,
        marksPerQ: 1,
        totalMarks: 10
    },
    'B': {
        title: 'Section B: Value-based Choices',
        desc: 'This section contains multiple-choice questions with 4 options. Select the choice that best aligns with core values, leadership qualities, and personal excellence.',
        count: 15,
        marksPerQ: 2,
        totalMarks: 30
    },
    'C': {
        title: 'Section C: Scenario-based Case Studies',
        desc: 'This section presents real-world scenarios or professional dilemmas with 2 options. Select the action that is ethically correct and shows team responsibility.',
        count: 15,
        marksPerQ: 2,
        totalMarks: 30
    }
};

document.addEventListener('DOMContentLoaded', () => {
    fetchQuestions();
    
    document.getElementById('next-btn').addEventListener('click', () => {
        currentIndex++;
        renderQuestion();
    });
    
    document.getElementById('finish-btn').addEventListener('click', submitScore);
});

async function fetchQuestions() {
    try {
        const response = await fetch('api.php?action=get_questions');
        const data = await response.json();
        
        if (data.status === 'success') {
            questions = data.data;
            document.getElementById('loading').style.display = 'none';
            if (questions.length > 0) {
                document.getElementById('quiz-container').style.display = 'block';
                renderQuestion();
            } else {
                document.getElementById('loading').innerText = 'No questions available.';
                document.getElementById('loading').style.display = 'block';
            }
        } else {
            alert('Error loading questions');
        }
    } catch (err) {
        console.error(err);
        alert('Failed to connect to server.');
    }
}

function renderQuestion() {
    if (currentIndex >= questions.length) return;
    
    const q = questions[currentIndex];
    
    // Check for Section transitions
    if (q.section !== currentSection && !showingIntro) {
        showingIntro = true;
        currentSection = q.section;
        renderSectionIntro(q.section);
        return;
    }
    
    showingIntro = false;
    answered = false;
    
    // Set Section Header details
    const secInfo = sectionDetails[q.section] || { title: `Section ${q.section}`, marksPerQ: 1 };
    const secIndicator = document.getElementById('section-indicator');
    secIndicator.innerText = secInfo.title;
    secIndicator.style.display = 'block';
    
    // Set marks value indicator
    document.getElementById('question-value').innerText = `Marks: ${secInfo.marksPerQ}`;
    
    // Set question progress text
    document.getElementById('progress-text').innerText = `Question ${currentIndex + 1} of ${questions.length}`;
    
    // Render question text
    document.getElementById('question-text').innerText = q.question;
    document.getElementById('question-text').style.display = 'block';
    
    // Render options
    const optionsContainer = document.getElementById('options-container');
    optionsContainer.innerHTML = '';
    optionsContainer.style.display = 'grid';
    
    const options = [
        { key: 'A', text: q.option_a },
        { key: 'B', text: q.option_b },
        { key: 'C', text: q.option_c },
        { key: 'D', text: q.option_d }
    ].filter(opt => opt.text && opt.text.trim() !== ''); // Filter out empty options
    
    options.forEach(opt => {
        const div = document.createElement('div');
        div.className = 'option';
        div.innerHTML = `<strong style="margin-right: 15px; background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 4px;">${opt.key}</strong> ${opt.text}`;
        div.addEventListener('click', () => selectOption(opt.key, q.correct_option, div, secInfo.marksPerQ));
        optionsContainer.appendChild(div);
    });
    
    document.getElementById('feedback').innerText = '';
    document.getElementById('feedback').style.color = '';
    document.getElementById('feedback').style.display = 'block';
    
    document.getElementById('next-btn').style.display = 'none';
    document.getElementById('finish-btn').style.display = 'none';
}

function renderSectionIntro(section) {
    const secInfo = sectionDetails[section];
    
    const secIndicator = document.getElementById('section-indicator');
    secIndicator.style.display = 'none';
    
    document.getElementById('question-value').innerText = '';
    document.getElementById('progress-text').innerText = '';
    
    document.getElementById('question-text').style.display = 'none';
    
    const optionsContainer = document.getElementById('options-container');
    optionsContainer.style.display = 'block';
    optionsContainer.innerHTML = `
        <div style="text-align: center; padding: 2rem 1rem; animation: fadeIn 0.5s ease-in-out;">
            <div style="display: inline-block; background: rgba(255, 107, 0, 0.15); color: #ff8c00; font-weight: bold; padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 1.5rem; letter-spacing: 1px;">
                New Exam Section
            </div>
            <h1 style="font-size: 1.8rem; margin-bottom: 1rem; color: #fff; font-weight: 700;">${secInfo.title}</h1>
            <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.6; max-width: 500px; margin: 0 auto 2rem;">
                ${secInfo.desc}
            </p>
            
            <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 2.5rem;">
                <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); padding: 15px 25px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Questions</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary);">${secInfo.count}</div>
                </div>
                <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); padding: 15px 25px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Marks / Q</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary);">${secInfo.marksPerQ}</div>
                </div>
                <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); padding: 15px 25px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Total Marks</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--success);">${secInfo.totalMarks}</div>
                </div>
            </div>
            
            <button id="start-sec-btn" class="btn" style="width: auto; padding: 12px 35px; background: var(--primary); font-size: 1.05rem;">
                Begin Section
            </button>
        </div>
    `;
    
    document.getElementById('feedback').style.display = 'none';
    document.getElementById('next-btn').style.display = 'none';
    document.getElementById('finish-btn').style.display = 'none';
    
    document.getElementById('start-sec-btn').addEventListener('click', () => {
        showingIntro = false;
        renderQuestion();
    });
}

function selectOption(selectedKey, correctKey, element, marksPerQ) {
    if (answered) return;
    answered = true;
    
    const options = document.querySelectorAll('.option');
    options.forEach(opt => opt.classList.add('disabled'));
    
    if (selectedKey === correctKey) {
        element.classList.add('correct');
        score += marksPerQ; // Add correct marks based on section weight
        document.getElementById('feedback').innerText = `Correct! (+${marksPerQ} Marks)`;
        document.getElementById('feedback').style.color = 'var(--success)';
    } else {
        element.classList.add('wrong');
        document.getElementById('feedback').innerText = `Wrong! The correct answer is Option ${correctKey}.`;
        document.getElementById('feedback').style.color = 'var(--danger)';
        
        // Highlight correct answer
        options.forEach(opt => {
            // Check if opt starts with correct key (like "A" or "B")
            if (opt.innerText.trim().startsWith(correctKey)) {
                opt.classList.add('correct');
            }
        });
    }
    
    if (currentIndex < questions.length - 1) {
        document.getElementById('next-btn').style.display = 'inline-block';
    } else {
        document.getElementById('finish-btn').style.display = 'inline-block';
    }
}

async function submitScore() {
    try {
        const response = await fetch('api.php?action=submit_score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ score: score, total: 70 }) // Max marks is 70 according to paper scheme
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            document.getElementById('quiz-container').style.display = 'none';
            document.getElementById('result-container').style.display = 'block';
            document.getElementById('final-score').innerText = `${score} / 70`;
        } else {
            alert('Error saving score');
        }
    } catch (err) {
        console.error(err);
        alert('Failed to submit score.');
    }
}
