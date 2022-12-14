const xalert = async (msg)=>{
    let el = document.querySelector("#xalert");
    if (el === null) {
        el = document.createElement("div");
        el.innerHTML = '<div id="xalert" style="position:fixed;width:100%;height:100%;top:0;left:0;z-index:999;background:#0003;display:none;align-items:center;justify-content:center"><div id="xalert__box" style="background:#fff;border-radius:10px;padding:10px;max-width:500px;min-width:200px"><div id="xalert__content" style="padding:5px"></div><div id="xalert__btn" class="btn" style="width:fit-content">OK</div></div></div>';
        document.querySelector("body").append(el.childNodes[0]);
        $("#xalert__btn").click(function() {
            $("#xalert").hide();
        })
    }
    $("#xalert__content").text(msg);
    $("#xalert").css("display", "flex");
    await new Promise((resolve, reject)=>{
        let i = setInterval(()=>{
            if ($("#xalert").css("display") === "none") {
                resolve();
                clearInterval(i);
            }
        }, 100);
    });
}

const origin = ()=>{
    let s = location.href;
    if (s.endsWith('#'))
        return s.substring(0, s.length - 1);
    else 
        return s;
}

// Click title => Jump to index
$(".forum-title").click(function() {
    location.href = forumHost;
});

// Click navigation => Jump to section
$(".forum-nav>div").click(function() {
    location.href = forumHost + "/section/" + $(this).attr("data-sid");
});

// Click section title => Jump to section
$(".forum-section-title").click(function() {
    location.href = forumHost + "/section/" + $(this).attr("data-sid");
});

$(".forum-thread-title").click(function() {
    location.href = forumHost + "/thread/" + $(this).attr("data-tid");
});

// Login
$(".login-submit").click(function() {
    let sha1 = new Hashes.SHA1();
    let md5 = new Hashes.MD5();
    $(".login-warn").text("");
    $.post(
        forumHost + "/api/login",
        {
            email: $("#login-username").val(),
            password: md5.hex(sha1.hex($("#login-password").val()))
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            $(".login-warn").text(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
});

// Logout
$(".logout-submit").click(function() {
    $.post(forumHost + "/api/logout")
    .done(function() {
        location.href = origin();
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

// To admin
$(".to-admin").click(function() {
    location.href = forumHost + "/admin";
})

// Start thread
$("#new-thread-submit").click(function() {
    $.post(
        forumHost + "/api/start_thread",
        {
            title: $("#new-thread-title").val(),
            content: $("#new-thread-content").val(),
            sid: $(this).attr("data-sid")
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

// Reply
$("#reply-submit").click(function() {
    $.post(
        forumHost + "/api/reply",
        {
            content: $("#reply-content").val(),
            sid: $(this).attr("data-sid"),
            tid: $(this).attr("data-tid")
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

// Delete thread
$(".thread-delete-btn").click(function() {
    if ($(this).attr("data-cnt") === "1") {
        let b = confirm("This is the head thread, if you delete it, the whole thread will be disappear\nAre you sure?");
        if (b === false)
            return;
    }

    $.post(
        forumHost + "/api/delete_thread",
        {
            tid: $(this).attr("data-tid")
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

// Start section
$("#new-section-submit").click(function() {
    $.post(
        forumHost + "/api/add_section",
        {
            name: $("#new-section-title").val(),
            description: $("#new-section-desc").val()
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

// Delete section
$(".section-delete-btn").click(function() {
    $.post(
        forumHost + "/api/delete_section",
        {
            sid: $(this).attr("data-sid")
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

// Rename section
$(".section-rename-btn").click(function() {
    let name = prompt("New name");
    if (name === null)
        return;

    $.post(
        forumHost + "/api/update_section_name",
        {
            sid: $(this).attr("data-sid"),
            name
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".section-redesc-btn").click(function() {
    let desc = prompt("New description");
    if (desc === null)
        return;

    $.post(
        forumHost + "/api/update_section_desc",
        {
            sid: $(this).attr("data-sid"),
            desc
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".thread-update-btn").click(function() {
    let content = prompt("New content");
    if (content === null)
        return;

    $.post(
        forumHost + "/api/update_thread",
        {
            tid: $(this).attr("data-tid"),
            content
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".rename-btn").click(function() {
    let name = prompt("New name");
    if (name === null)
        return;

    $.post(
        forumHost + "/api/rename",
        {
            uid: $(this).attr("data-uid"),
            name
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".change-pass-btn").click(function() {
    let pass = prompt("New password");
    if (pass === null)
        return;

    let sha1 = new Hashes.SHA1();
    let md5 = new Hashes.MD5();

    $.post(
        forumHost + "/api/update_pass",
        {
            uid: $(this).attr("data-uid"),
            password: md5.hex(sha1.hex(pass))
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$("#admin-site-options-submit").click(function() {
    $.post(
        forumHost + "/api/update_options",
        {
            sitename: $("#admin-sitename-input").val()
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$("#admin-moderator-submit").click(async function() {
    if ($("#admin-moderator-sid").val() === "0") {
        await xalert("Please select section");
        return;
    }

    $.post(
        forumHost + "/api/add_mod",
        {
            sid: $("#admin-moderator-sid").val(),
            uid: $("#admin-moderator-uid").val()
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".admin-moderator-delete").click(function() {
    $.post(
        forumHost + "/api/delete_mod",
        {
            sid: $(this).attr("data-sid"),
            uid: $(this).attr("data-uid")
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".admin-user-delete").click(function() {
    $.post(
        forumHost + "/api/delete_user",
        {
            uid: $(this).attr("data-uid")
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".admin-user-rename").click(function() {
    let name = prompt("New name");
    if (name === null)
        return; 

    $.post(
        forumHost + "/api/rename",
        {
            uid: $(this).attr("data-uid"),
            name
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".admin-user-repass").click(function() {
    $.post(
        forumHost + "/api/reset_pass",
        {
            uid: $(this).attr("data-uid")
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})

$(".admin-user-repriv").click(async function() {
    let name = prompt("New privilege:\n(0: User, 1: Admin)");
    if (name === null)
        return; 
    if (name !== "0" && name !== "1") {
        await xalert("Invalid input");
        return;
    }

    $.post(
        forumHost + "/api/set_privilege",
        {
            uid: $(this).attr("data-uid"),
            privilege: name
        }
    )
    .done(async function(r) {
        r = JSON.parse(r);
        if (r.status === "success") {
            await xalert("Success");
            location.href = origin();
        }
        else
            await xalert(r.msg);
    })
    .fail(async function() {
        await xalert("Ajax Error Occurred:\nPlease check your network\nOr remote server has been downed");
    });
})
