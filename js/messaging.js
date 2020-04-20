
/**
 * This function will enable / disable the send message button depending on the length of the message input field
 */
function checkMinLength() {
    let sendButton = document.getElementById('messageSendButton');
    let messageInput = document.getElementById('messageInput');
    sendButton.disabled = messageInput.value.trim().length <= 0;
}

function getConversationHistory(otherID) {
    let messagesList = document.getElementById("message-list");
    fetch(`getConversationHistory.php?id=${otherID}`).then((result) => {
        return result.text();
    }).then((data) => {
        let messages = JSON.parse(data);
        console.log(messages)
        messagesList.innerHTML = ""
        messages.forEach(message => messagesList.append(new Message(message._messageSenderName, message._messageContent, message._messageDatecreated, message._messageImage).build()))
    }).catch((error) => {
        console.error(error);
    })

    setTimeout(getConversationHistory.bind(null, otherID), 2000)
}

/**
 *  This function parses the contents of the messageInput field, makes a POST request to sendMessage.php
 *  and when the transaction completes, it will refresh the users conversation history
 */
function sendMessage(recipientID) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "sendMessage.php", true);
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.responseText)
        }
    }
    let formData = new FormData();
    let messageInput = document.getElementById("messageInput");
    let content = stripHTML(messageInput.value);
    let imageInput = document.getElementById("imageInput")
    let imageValue = imageInput.files[0];
    formData.append("content", content);
    formData.append("id", other);
    formData.append("img", imageValue)

    xhr.send(formData);
    getConversationHistory(other)
    messageInput.value = ""
    messageInput.focus()
    checkMinLength()
}

class Message {
    constructor(senderName, content, timeSent, img) {
        this.sender = senderName;
        this.content = content;
        this.img = img
        this.timeSent = timeSent;
    }

    build() {
        let listElement = document.createElement("li");
        listElement.className = "list-group-item list-element col-sm-12";
        let div = document.createElement("div");
        let senderName = document.createElement("h3");
        let timeSent = document.createElement("p");
        let img = document.createElement("img");
        img.className = "img-fluid"
        let content = document.createElement("p");

        senderName.innerHTML = this.sender;
        timeSent.innerHTML = this.timeSent;
        content.innerHTML = this.content;
        if (this.img != null) {
            img.src = `Images/${this.img}`
        }

        listElement.append(div);
        div.append(senderName);
        div.append(timeSent);
        div.append(content);
        div.append(content);
        div.append(img);
        return listElement;
    }
}

function stripHTML(html)
{ return html.replace(/(<([^>]+)>)/ig,"");
}

window.addEventListener("load", getConversationHistory.bind(null, other));