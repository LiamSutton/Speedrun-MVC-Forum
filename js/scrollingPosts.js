function getNextPosts(categoryID, page, limit, dateOrder, comment, title) {
    page++;
    fetch(`getNextPosts.php?categoryID=${categoryID}&page=${page}&limit=${limit}&date=${dateOrder}&comment=${comment}&title=${title}`).then((result) => {
        return result.text()
    }).then((data) => {
        const posts = JSON.parse(data);
        let postList = document.getElementById('postList');
        if (posts.length === 0) {
            let alert = document.createElement("div");
            alert.setAttribute("role", "alert");
            alert.className = "alert alert-danger";
            alert.innerHTML = "Theres nothing more UwU";
            postList.appendChild(alert)
        }
        posts.forEach((post) => {
            postList.appendChild(new Post(post._id, post._title, post._posterID, post._posterFullName, post._content, post._replyCount).build());
        });

    }).catch((error) => {
        console.error(error)
    })
}


class Post {
    constructor(id, title, posterID, posterFullName, content, replyCount) {
        this.id = id;
        this.title = title;
        this.posterID = posterID;
        this.posterFullName = posterFullName;
        this.content = content;
        this.replyCount = replyCount;
    }

    build() {
        let list = document.createElement("li");
        let div = document.createElement("div");
        let postLink = document.createElement("a");
        let title = document.createElement("h1");
        let accountLink = document.createElement("a");
        let posterName = document.createElement("h4");
        let content = document.createElement("h4");
        let commentCount = document.createElement("h5");



        list.className = "list-group-item list-element col-sm-12";
        title.className = "list-group-item-heading text-center";
        posterName.className = "text-muted text-center";
        content .className = "lead text-center";
        commentCount.className = "text-center";

        postLink.href = `fullpost.php?id=${this.id}`;
        accountLink.href = `account.php?id=${this.posterID}`;
        title.innerHTML = this.title;
        posterName.innerHTML = this.posterFullName;
        content.innerHTML = this.content;
        commentCount.innerHTML = `Number of Comments: ${this.replyCount}`;

        list.append(div);
        div.appendChild(postLink);
        postLink.appendChild(title);
        div.appendChild(accountLink);
        accountLink.appendChild(posterName);
        div.appendChild(content);
        div.appendChild(commentCount);

        return list;
    }
}