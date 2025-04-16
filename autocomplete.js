let availabeKeyWorlds = [
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
    'Offenses'
];

const resultBox = document.querySelector(".result-box");
const inputBox = document.getElementId("input-box");

inputBox.onkeyUp = function(){
    let result = [];
    let input = inputBox.value;

    if(input.length){
        result = availabeKeyWorlds.filter((keyword) => {
            keyword.toLowerCase(input).includes(input);
        });
        console.log(result);
    }
}
