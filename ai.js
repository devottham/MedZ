async function sendMessage() {
    const userInput = document.getElementById('userInput').value.trim();
    const chatBox = document.getElementById('chatBox');

    // Check for empty input
    if (userInput === '') {
        console.warn("User input is empty.");
        return;
    }

    // Add user's message to the chat box
    const userMessageElement = document.createElement('p');
    userMessageElement.classList.add('user-message');
    userMessageElement.innerText = `You: ${userInput}`;
    chatBox.appendChild(userMessageElement);

    // Show typing indicator with dot animation
    const typingIndicator = document.createElement('div');
    typingIndicator.id = 'typing-indicator';
    typingIndicator.innerText = "  MedZ is typing";
    chatBox.appendChild(typingIndicator);

    let dotCount = 0;
    let increasing = true;

    const intervalId = setInterval(() => {
        dotCount = increasing ? dotCount + 1 : dotCount - 1;
        if (dotCount === 3) increasing = false;
        else if (dotCount === 0) increasing = true;

        typingIndicator.innerText = "  MedZ is typing" + '.'.repeat(dotCount);
    }, 500); // Update every 500ms

    chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll

    // Call AI server API
    try {
        console.log("Sending user input to AI server...");

        const requestBody = JSON.stringify({
            messages: [
                { "role": "user", "content": userInput }
            ]
        });

        console.log("Request body:", requestBody);

        const response = await fetch("http://localhost:1234/v1/chat/completions", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: requestBody
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error("Error fetching AI response:", response.status, response.statusText, errorText);
            throw new Error("Failed to fetch AI response");
        }

        const data = await response.json();
        const botResponse = data.choices[0].message.content;

        console.log("AI response received:", botResponse);

        // Remove typing indicator and stop dot animation
        clearInterval(intervalId);
        typingIndicator.remove();

        // Add bot's response to the chat box
        const botMessageElement = document.createElement('p');
        botMessageElement.classList.add('bot-message');
        botMessageElement.innerText = `MedZ: ${botResponse}\n\nConsult Doctors for Further Information.`;
        chatBox.appendChild(botMessageElement);

    } catch (error) {
        clearInterval(intervalId); // Stop dot animation
        typingIndicator.remove(); // Remove typing indicator if an error occurs
        const botMessageElement = document.createElement('p');
        botMessageElement.classList.add('bot-message');
        botMessageElement.innerText = "MedZ: Sorry, there was an issue processing your request.";
        chatBox.appendChild(botMessageElement);
        console.error("Error during API call:", error.message);
    }

    // Clear the input field for the next message
    document.getElementById('userInput').value = '';

    // Auto-scroll chat box to the bottom after sending message
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Optional: Function to fetch model info (can be used to test server communication)
async function getModelInfo() {
    try {
        const response = await fetch('http://localhost:1234/v1/models');
        if (!response.ok) {
            const errorText = await response.text();
            console.error("Error fetching model info:", response.status, response.statusText, errorText);
            throw new Error('Failed to fetch model info');
        }
        const data = await response.json();
        console.log("Model info:", data);
    } catch (error) {
        console.error("Error in getModelInfo:", error.message);
    }
}

// Call getModelInfo on page load (or you can call this via a button click)
document.addEventListener("DOMContentLoaded", getModelInfo);
