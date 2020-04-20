async function getAllConversations() {
    const userID = "<?php echo $_SESSION['id'] ?>";
    let conversationList = document.getElementById('conversations');
    // Make a call to loadInbox.php and retrieve a list of "conversations" ordered by Newest -> Oldest.
    fetch(`getConversations.php?id=${userID}`).then((result) => {
        return result.text()
    }).then((data) => {
        console.log(data)
        let response = JSON.parse(data);
        conversationList.innerHTML = "";
        response.forEach(conversation => conversationList.append(new Conversation(conversation.id, conversation.username, conversation.fullname, conversation.lastmsg, conversation.unopened).build()))
    }).catch((error) => {
        console.log(error)
    })

    // Recursive call so that function polls every 5 seconds
    setTimeout(getAllConversations, 5000);
}

class Conversation {
    constructor(id, author, name, d, u) {
        this.id = id;
        this.author = author
        this.name = name;
        this.d = d;
        this.u = u
    }

    build() {
        let list = document.createElement("li");
        let div = document.createElement("div");
        let name = document.createElement("a");
        let fullname = document.createElement("p");
        let stamp = document.createElement("p");
        let unopened = document.createElement("p");

        fullname.innerHTML = this.name;
        name.innerHTML = this.author;
        stamp.innerHTML = this.d;
        unopened.innerHTML = this.u
        list.className = "list-group-item list-element col-sm-12";
        name.className = "list-group-item-heading text-center";
        name.href = `conversation.php?id=${this.id}`
        list.append(div);
        div.append(fullname);
        div.append(name);
        div.append(stamp)
        div.append(unopened)

        return list
    }
}

function stripHTML(html)
{ return html.replace(/(<([^>]+)>)/ig,"");
}