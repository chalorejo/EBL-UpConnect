let availableKeyWords = [
    'Application',
    'Dorm',
    'Dormitory',
    'Accomodation',
    'Requirements',
    'Permit',
    'Forms',
    'Overnight',
    'Request',
    'Information',
    'Policy',
    'Policies',
    'Responsibilities',
    'Responsibility',
    'Reminder',
    'Reminders',
    'Offenses',
];

const resultBox = document.querySelector(".result-box");
const inputBox = document.getElementById("input-box");

inputBox.onkeyup = function () {
    let result = [];
    let input = inputBox.value;

    if (input.length) {
        result = availableKeyWords.filter((keyword) =>
            keyword.toLowerCase().includes(input.toLowerCase())
        );
    }

    display(result);
};

function display(result) {
    if (!result.length) {
        resultBox.innerHTML = ""; // Clear suggestions if nothing matches
        return;
    }

    const content = result.map((list) => {
        return `<li onclick="selectInput(this)">${list}</li>`;
    });

    resultBox.innerHTML = `<ul>${content.join('')}</ul>`;
}

function selectInput(element) {
    inputBox.value = element.innerHTML;
    resultBox.innerHTML = '';
}

// Close search results when clicking outside
document.addEventListener('click', function(event) {
    const searchBox = document.getElementById('searchBox');
    const searchIcon = document.querySelector('.fa-search').parentElement;
    
    if (!searchBox.contains(event.target) && event.target !== searchIcon && !searchIcon.contains(event.target)) {
        searchBox.style.display = 'none';
    }
});

// Close search results when pressing Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const searchBox = document.getElementById('searchBox');
        searchBox.style.display = 'none';
    }
});
