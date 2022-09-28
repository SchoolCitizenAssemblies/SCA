function init() {
    locationInput.dispatchEvent(new Event("input"));
    expertiseInput.dispatchEvent(new Event("input"));
    studentInteractionCheckbox.dispatchEvent(new Event("click"));
    
    for(const nameInput of resourceNameInputs) {
        nameInput.dispatchEvent(new Event("input"));
    }
    
    for(const linkInput of resourceLinkInputs) {
        linkInput.dispatchEvent(new Event("input"));
    }
}

var isExpertiseValid = false;
var isLocationValid = false;
const saveButton = document.querySelector(".profile button");

function buttonCheck() {
    const resourceNameInputs = document.querySelectorAll('input[name="resourceName"]');
    const resourceLinkInputs = document.querySelectorAll('input[name="resourceLink"]');

    for(const nameInput of resourceNameInputs) {
        if(nameInput.style.borderColor == "red") {
            saveButton.disabled = true;
            return;
        }
    }
    
    for(const linkInput of resourceLinkInputs) {
        if(linkInput.style.borderColor == "red") {
            saveButton.disabled = true;
            return;
        }
    }

    saveButton.disabled = !(isExpertiseValid && isLocationValid)
}

// validation on expertise
const expertiseInput = document.querySelector("input[name='expertise']");
expertiseInput.addEventListener("input", validateExpertiseInput);

function validateExpertiseInput(event) {
    // not null
    if(event.target.value == '') {
        expertiseInput.style.borderColor = "red";
        isExpertiseValid = false;
    } else {
        expertiseInput.style.borderColor = "#666666";
        isExpertiseValid = true;
    }
    buttonCheck();
}

// validation on location
const locationInput = document.querySelector("input[name='location']");
locationInput.addEventListener("input", validateLocationInput);

function validateLocationInput(event) {
    if(!validPostcode(event.target.value)) {
        locationInput.style.borderColor = "red";
        isLocationValid = false;
    } else {
        locationInput.style.borderColor = "#666666";
        isLocationValid = true;
    }
    buttonCheck();
}

function validPostcode(outcode) {
    function generatePattern(string) {
        pattern = "";

        for (const char of string) {
            if((/[a-zA-Z]/).test(char)) {
                pattern += "A"
            } else if((/[0-9]/).test(char)) {
                pattern += "N"
            } else {
                pattern += "S" // symbol
            }
        }

        return pattern;
    }

    validPatterns = [
        "AN",
        "ANN",
        "AAN",
        "AANN",
        "ANA",
        "AANA"
    ];
    outcode = outcode.toUpperCase();
    outcodePattern = generatePattern(outcode);

    return validPatterns.includes(outcodePattern)
}

saveButton.addEventListener("click", submit);

function submit() {
    // if disabled return

    inputs = document.querySelectorAll('input:not([name="studentInteraction"])');
    xhr = new XMLHttpRequest();
    formData = new FormData();

    formData.append("expertise", inputs[0].value);
    formData.append("org", inputs[1].value);
    formData.append("teacherAdvice", + inputs[7].checked);
    formData.append("projectWork", + inputs[8].checked);
    formData.append("studentOnline", + inputs[9].checked);
    formData.append("studentF2F", + inputs[10].checked);
    formData.append("studentResources", + inputs[11].checked);
    formData.append("location", inputs[12].value);

    ages = "";
    for(i=2; i<7; i++) {
        if(inputs[i].checked) {
            if(i > 2) {
                ages += ",";
            }
            ages += "KS"+(i-1);
        }
    }
    formData.append("ages", ages);

    xhr.open("POST", "../phpScripts/updateExpert.php");
    xhr.send(formData);

    // new update for expert resources

    // foward to mte
}

const studentInteractionCheckbox = document.querySelector('input[name="studentInteraction"]');
const interactionCheckboxes = [
    document.querySelector('input#online'),
    document.querySelector('input#f2f'),
    document.querySelector('input#resources')
];
const interactionLabels = [
    document.querySelector('label#online'),
    document.querySelector('label#f2f'),
    document.querySelector('label#resources')
];
studentInteractionCheckbox.addEventListener("click", updateInteractionVisibilities);

function updateInteractionVisibilities(event) {
    for(let i = 0; i < 3; i++) {
        checkbox = interactionCheckboxes[i];
        label = interactionLabels[i];

        checkbox.checked = false;

        if(studentInteractionCheckbox.checked) {
            checkbox.style.display = "initial";
            label.style.display = "initial";
        } else {
            checkbox.style.display = "none";
            label.style.display = "none";
        } 
    }  
}


const newResourceButton = document.querySelector(".addResource");
newResourceButton.addEventListener("click", addResource);

function addResource(event) {
    // make table row
    const newRow = document.createElement("tr");

    // make table data
    const nameData = document.createElement("td");
    // make text input
    const nameInput = document.createElement("input");
    nameInput.setAttribute("type","text");
    nameInput.setAttribute("name", "resourceName");
    nameInput.setAttribute("placeholder", "Resource Name");
    nameInput.addEventListener("input", checkResourceName);
    // append input to data
    nameData.appendChild(nameInput);
    // append data to row
    newRow.appendChild(nameData)

    // make table data
    const linkData = document.createElement("td");
    // make text input
    const linkInput = document.createElement("input");
    linkInput.setAttribute("type","text");
    linkInput.setAttribute("name", "resourceLink");
    linkInput.setAttribute("placeholder", "Resource Link");
    linkInput.addEventListener("input", checkResourceLink);
    // append input to data
    linkData.appendChild(linkInput);
    // append data to row
    newRow.appendChild(linkData)

    // make table data
    const deleteData = document.createElement("td");
    // make text input
    const deleteImg = document.createElement("img");
    deleteImg.setAttribute("src", "assets/remove.png")
    deleteImg.addEventListener("click", deleteResource);
    // append input to data
    deleteData.appendChild(deleteImg);
    // append data to row
    newRow.appendChild(deleteData)

    // append row to table
    document.querySelector(".resourceTable").appendChild(newRow);
}

const deleteImgs = document.querySelectorAll('img[src="assets/remove.png"]');
for(const img of deleteImgs) {
    img.addEventListener("click", deleteResource);
}

function deleteResource(event) {
    event.currentTarget.parentElement.parentElement.remove()
    buttonCheck();
}

const resourceNameInputs = document.querySelectorAll('input[name="resourceName"]');
const resourceLinkInputs = document.querySelectorAll('input[name="resourceLink"]');

for(const nameInput of resourceNameInputs) {
    nameInput.addEventListener("input", checkResourceName);
}

for(const linkInput of resourceLinkInputs) {
    linkInput.addEventListener("input", checkResourceLink);
}

function checkResourceLink(event) {
    url = event.currentTarget.value;

    if(!url.startsWith("http://") || !url.startsWith("https://")) {
        url = "http://" + url;
    }

    if(url.substring(7,11) != "www.") {
        url = url.slice(0,7) + "www." + url.slice(7);
    }

    target = event.path[0] || event.composedPath()[0];
    fetch("../phpScripts/getStatus.php?url="+url)
    .then(response => response.text())
    .then(linkStatus => {
        console.log(linkStatus);

        if(200 <= linkStatus && linkStatus <= 299) {
            target.style.borderColor = "green";
        } else if (300 <= linkStatus && linkStatus <= 399) {
            target.style.borderColor = "yellow";
        } else if(399 < linkStatus) {
            target.style.borderColor = "red";
        } else {
            target.style.borderColor = "#666666";
        }
    });
    buttonCheck();
}

function checkResourceName(event) {
    target = event.path[0] || event.composedPath()[0];
    if(target.value == '') {
        event.composedPath()[0].style.borderColor = "red"; 
    } else {
        target.style.borderColor = "#666666";
    }
    buttonCheck();
}

init();