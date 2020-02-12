let page = Number(<?php echo $view->currentPage?>);
let pageCount = Number(<?php echo $view->pageCount?>);
let categoryID = Number(<?php echo $view->currentCategory?>);
let limit = Number(<?php echo $view->limit?>);
let dateOrder = Number(<?php echo $view->dateOrder?>);
let title = "<?php echo $view->title?>";
let comment = <?php echo $view->commentOrder?>;

function getNextPosts() {
    page++;
    fetch(`getNextPosts.php?categoryID=${categoryID}&page=${page}&limit=${limit}&date=${dateOrder}&comment=${comment}&title=${title}`).then((result) => {
        return result.text()
    }).then((data) => {
        const posts = JSON.parse(data);
        let a = document.getElementById('a');
        if (posts.length === 0) {
            let alert = document.createElement("div");
            alert.setAttribute("role", "alert");
            alert.className = "alert alert-danger";
            alert.innerHTML = "Theres nothing more UwU";
            a.appendChild(alert)
        }
        posts.forEach((post) => {
            a.appendChild(new Post(post._id, post._title, post._posterID, post._posterFullName, post._content, post._replyCount).build());
        });

    }).catch((error) => {
        console.error(error)
    })
}

document.addEventListener('scroll', function (event) {
    if (document.body.scrollHeight ==
        document.body.scrollTop +
        window.innerHeight) {
        if (page <= pageCount) {
            getNextPosts();
        }
    }
});

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
        let a = document.createElement("li");
        let b = document.createElement("div");
        let c = document.createElement("a");
        let d = document.createElement("h1");
        let e = document.createElement("a");
        let f = document.createElement("h4");
        let g = document.createElement("h4");
        let h = document.createElement("h5");

        a.className = "list-group-item list-element col-sm-12";
        d.className = "list-group-item-heading text-center";
        f.className = "text-muted text-center";
        g.className = "lead text-center";
        h.className = "text-center";

        c.href = `fullpost.php?id=${this.id}`;
        e.href = `account.php?id=${this.posterID}`;
        // c.innerHTML = this.id;
        d.innerHTML = this.title;
        // e.innerHTML = this.posterID;
        f.innerHTML = this.posterFullName;
        g.innerHTML = this.content;
        h.innerHTML = `Number of Comments: ${this.replyCount}`;

        a.append(b);
        b.appendChild(c);
        c.appendChild(d);
        b.appendChild(e);
        e.appendChild(f);
        b.appendChild(g);
        b.appendChild(h);

        return a;
    }
}