// Predefined dataset of questions and corresponding answers
const dataset = [
    {
        questionPattern: /what is your name/,
        answer: "I am your NLP chatbot."
    },

    {
        questionPattern: /how are you|chatbot/,
        answer: "I'm just a program, but I'm functioning well! Thanks for asking. Use for barangay questions only"
    },
    {
        questionPattern: /what time is it/,
        answer: () => {
            const now = new Date();
            return `It's ${now.toLocaleTimeString()}.`;
        }
    },
    {
        questionPattern: /jobseeker/,
        answer: "• Find Jobs <br>\
                    • Applying to Jobs<br>\
                    • Job Offers and Orders<br>\
                    • Ranking <br>\
                    • Feedback",
    },
    {
        questionPattern: /find jobs/,
        answer: "Looking for jobs? You can search available job postings from different employers in our job list. Select which type of work you are looking for, and its location, and the job posts will be displayed. <br>\
            •What jobs are available?"
    },
    {
        questionPattern: /what jobs are available?/,
        answer: "Here are the jobs available for you:<br>\
            SKILLED: Welder, Electrician, Plumber, Carpenter, Refrigerator and Aircon Service Provider, Driver, Lineman <br>\
            UNSKILLED: Laundry Staff, Janitor, Food Services (Dishwasher and Waiter), Factory Worker, House Keeper, Construction Worker, Security Guard"
    },
    {
        questionPattern: /applying to jobs/,
        answer: "To apply to a job, just press an available job post. Click on the Apply Now button, and the employer will be notified about your intent. The employer will be able to see your contact details, resume and other files."
    },
    {
        questionPattern: /job offers and orders/,
        answer: "If the employer accepts your application, a job offer will be sent to you specifying the agreement details. You may accept or decline the offer. Accepting the offer will activate the job order that reflects the status of the ongoing job post."
    },
    {
        questionPattern: /ranking/,
        answer: "View your latest rank in the Top Jobseekers page. Your rank here depends on the quality of work you provide according to the reviews of your employers.<br>\
            How can I increase my rank?"
    },
    {
        questionPattern: /how can i increase my rank/,
        answer: "You can increase your rank by doing great work in each job you accept. Happy employers will definitely give you favorable reviews."
    },
    {
        questionPattern: /feedback/,
        answer: "You can view all the feedback from the previous job orders in the Feedback page. The details of the job order finished will also display here. "
    },
    // Ad
    // Add more question-answer pairs as needed
];

// Predefined dataset of Filipino translations
const employerDataset = [
    {
        questionPattern: /ano ang pangalan mo/,
        answer: "Ako ang inyong NLP chatbot.",
    },
    {
        questionPattern: /kamusta ka|chatbot/,
        answer: "Ako ay isang programa lamang, ngunit maayos naman! Gamitin lamang sa mga pambarangay na katanungan. Salamat!",
    },
    {
        questionPattern: /anong oras na/,
        answer: () => {
            const now = new Date();
            return `Ang oras ngayon ay ${now.toLocaleTimeString()}.`;
        }
    },
    {
        questionPattern: /find jobseeker/,
        answer: "Find your ideal job prospect by searching the particular work you wish to employ. You can view the list of jobseekers that is arranged according to occupation.",
    },
    {
        questionPattern: /hiring jobseeker/,
        answer: "You can hire a jobseeker directly by pressing the Hire button in their profile details. This allows you to directly give the job post to your target jobseeker.<br>\
                You can also hire the jobseeker who has filed an application on your jobpost",
    },
    {
        questionPattern: /job posting/,
        answer: "Create a job post where jobseekers can apply to. You can specify here the type of work to be done, its location, salary and other relevant requirements. Once posted, the job post will appear in the job post list that can be viewed by jobseekers",
    },
    {
        questionPattern: /managing job applications/,
        answer: "To manage the job application, you can view the applications to your job post. Accepting a worker will automatically give the job offer to the accepted applicant.",
    },
    {
        questionPattern: /job order/,
        answer: "A job order is activated whenever the jobseeker accepts your job offer. This reflects the status of the job post as ongoing. To end the job order, give your worker a review of the work accomplished, along with the proof of the work done.",
    },
    
];

let selectedLanguage = null;

// Function to get a response based on user input
function getResponse(input) {
    const isEmployerQuestion = input.toLowerCase().includes('employer');
    const isJobseekerQuestion = input.toLowerCase().includes('jobseeker');

    if (isEmployerQuestion) {
        selectedJob = 'employer';
        return "Hi! I’m the HanapKITA Chatbot. I’m here to guide you on how to use our page.  <br>\
                     EMPLOYER OPTIONS <br>\
                    • Find Jobseekers <br>\
                    • Hiring Jobseekers<br>\
                    • Job Posting<br>\
                    • Managing Job Applications <br>\
                    • Job Order";
    } else if (isJobseekerQuestion) {
        selectedJob = 'jobseeker';
        return "Hi! I’m the HanapKITA Chatbot. I’m here to guide you on how to use our page.  <br>\
                    JOBSEEKER OPTIONS <br>\
                    • Find Jobs <br>\
                    • Applying to Jobs<br>\
                    • Job Offers and Orders<br>\
                    • Ranking <br>\
                    • Feedback";
    }

    if (!selectedJob) {
        return "Please select a user type: 'Employer' or 'Jobseeker'.";
    }

    const jobDataset = selectedJob === 'employer' ? employerDataset : dataset;
    
    for (const item of jobDataset) {
        // Constructing the regular expression for alternative patterns
        const regexPattern = new RegExp(`(?<!\\|)\\b(?:${item.questionPattern.source.replace(/\|/g, '|')}|${item.questionPattern.source})\\b(?!\\|)`, 'i');
        if (input.match(regexPattern)) {
            return typeof item.answer === 'function' ? item.answer() : item.answer;
        }
    }

    // Check if the question is not part of the selected language dataset
    if (selectedLanguage === 'filipino') {
        return "Paumanhin, sapagkat hindi ko naintindihan ang iyong katanungan.";
    } else {
        return "I'm sorry, I don't understand that question.";
    }
}

function displayMessage(sender, message) {
    const chatbox = document.getElementById("chatbox");
    const newMessage = document.createElement("div");
    const breaks = document.createElement("br");

    if (sender == "user") {
        newMessage.classList.add("user");

    } else {
        newMessage.classList.add("chatbot");
    }
    newMessage.innerHTML = message;
    chatbox.appendChild(newMessage);
    //chatbox.appendChild(breaks);

}

function sendMessage() {
    const userInput = document.getElementById("userInput").value;
    if (!userInput.trim()) return;

    displayMessage("user", userInput);

    const botResponse = getResponse(userInput);
    displayMessage("chatbot", botResponse);

    // Clear the input field
    document.getElementById("userInput").value = "";

    var scroll = document.getElementById("chatbox");
    scroll.scrollTop = scroll.scrollHeight;
}


function chat_bot() {
    var msgbox = document.getElementById("msg_box").style.display;

    if (msgbox == "none") {
        document.getElementById("msg_box").style.display = "block";
    } else {
        document.getElementById("msg_box").style.display = "none";
    }
}

document.getElementById("msg_box").style.display = "none";