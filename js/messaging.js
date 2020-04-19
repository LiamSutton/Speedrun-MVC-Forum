
/**
 * This function will enable / disable the send message button depending on the length of the message input field
 */
function checkMinLength() {
    let btn = document.getElementById('messageSendButton');
    let txt = document.getElementById('messageInput');
    btn.disabled = txt.value.trim().length <= 0;
}

function getConversationHistory(otherID) {
    let h = document.getElementById("r");
    fetch(`getConversationHistory.php?id=${otherID}`).then((result) => {
        return result.text();
    }).then((data) => {
        let messages = JSON.parse(data);
        h.innerHTML = ""
        messages.forEach(message => h.append(new Message(message._messageSenderName, message._messageContent, message._messageDatecreated).build()))
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
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "sendMessage.php", true);
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.responseText)
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    let i = document.getElementById("messageInput");
    let content = i.value
    console.log(content)
    xhr.send(`id=${recipientID}&content=${content}`);
    getConversationHistory(other)
    i.value = ""
    i.focus()
    checkMinLength()
}

class Message {
    constructor(senderName, content, timeSent) {
        this.sender = senderName;
        this.content = content;
        this.timeSent = timeSent;
    }

    build() {
        let listElement = document.createElement("li");
        listElement.className = "list-group-item list-element col-sm-12";
        let div = document.createElement("div");
        let senderName = document.createElement("h3");
        let timeSent = document.createElement("p");
        let content = document.createElement("p");

        senderName.innerHTML = this.sender;
        timeSent.innerHTML = this.timeSent;
        content.innerHTML = this.content;

        listElement.append(div);
        div.append(senderName);
        div.append(timeSent);
        div.append(content);

        return listElement;
    }
}
window.addEventListener("load", getConversationHistory.bind(null, other));