// document.getElementById('loginform').addEventListener('submit', function(e) {fetchlogin(e)});
// document.getElementById('registerform').addEventListener('submit', function(e) {fetchregister(e)});
// document.getElementById('accountexists').addEventListener('input', function(e) {fetchaccountexists(e)});
// document.getElementById('linkisloggedin').addEventListener('click', function(e) {fetchisloggedin(e)});
// document.getElementById('logoutbutton').addEventListener('click', function(e) {fetchlogout(e)});
// document.getElementById('addmoviebutton').addEventListener('click', function(e) {fetchaddmovie(e)});
document.getElementById('addshowing').addEventListener('submit', function(e) {fetchaddshowing(e)});

function fetchlogin(evt) {
    evt.preventDefault()
    var fd = new FormData();
    fd.append('username', loginuser.value);
    fd.append('password', loginpass.value);
    fetch('http://localhost:80/2021/PROJ2/api/api.php?action=login', 
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 401) {
            console.log('login failed');
            localStorage.removeItem('csrf');
            localStorage.removeItem('role');
            localStorage.removeItem('username');
            localStorage.removeItem('email');
            localStorage.removeItem('name');
            localStorage.removeItem('LoginID');
            return;
        }
        if(headers.status == 203) {
            console.log('registration required');
            // only need csrf
        }
        headers.json().then(function(body) {
            // BUG is this a 203 or 200?
            localStorage.setItem('csrf', body.Hash);
            localStorage.setItem('username', loginuser.value);
            localStorage.setItem('name', body.name);
            localStorage.setItem('email', body.email);
            localStorage.setItem('role', body.role);
        })
    })
    .catch(function(error) {
        console.log(error)
    });
}
function fetchregister(evt) {
    evt.preventDefault();
    var fd = new FormData();
    fd.append('name', regname.value);
    fd.append('email', regemail.value); 
    fd.append('username', regusername.value);
    fd.append('pass', regpass.value);
    fd.append('role', regrole.value);
    fetch('http://localhost:80/2021/PROJ2/api/api.php?action=register', 
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 400) {
            console.log('register failed');
            return;
        }
        if(headers.status == 201) {
            console.log('registration updated');
            return;
        }
    })
    .catch(error => console.log(error));
}
function fetchaccountexists(evt) {
    if(evt.srcElement.value.length > 3) {
    fetch('http://localhost:80/2021/PROJ2/api/api.php?action=accountexists&username='+ evt.srcElement.value, 
        {
            method: 'GET',
            credentials: 'include'
        })
        .then(function(headers) {
            if(headers.status == 204) {
                console.log('user does not exist');
                return;
            }
            if(headers.status == 400) {
                console.log('user exists');
                return;
            }
            headers.json().then(function(body) {
                console.log(body);
            })
        })
        .catch(error => console.log(error));
    }
}
function fetchisloggedin(evt) {
    fetch('http://localhost:80/2021/PROJ2/api/api.php?action=isloggedin', 
    {
        method: 'GET',
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 403) {
            console.log('not logged in');
            localStorage.removeItem('csrf');
            localStorage.removeItem('role');
            localStorage.removeItem('username');
            localStorage.removeItem('email');
            localStorage.removeItem('name');
            localStorage.removeItem('LoginID');
            return;
        }
        headers.json().then(function(body) {
            localStorage.setItem('csrf', body.Hash);
        })
    })
    .catch(error => console.log(error));
}

function fetchaddmovie(evt) {
    evt.preventDefault();
    addMname = document.getElementById("Mname");
    addMimage = document.getElementById("Mimage");
    var fd = new FormData();
    fd.append('Mname', addMname.value);
    fd.append('Mimage', addMimage.value); 
    fetch('http://localhost:80/2021/PROJ2/api/api.php?action=addmovie', 
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 400) {
            console.log('addmovie failed');
            return;
        }
        if(headers.status == 201) {
            console.log('newmovie updated');
            return;
        }
    })
    .catch(error => console.log(error));
}



fetch('http://localhost:80/2021/PROJ2/api/api.php?action=selectMID', 
    {
        method: 'GET',
        credentials: 'include'
    })
    .then((res)=>res.json())
    .then(movielist=>{
        console.log(movielist);
        let output="";
        for(i=0; i<movielist.length; i++) {
            output += `<option value=`+movielist[i].movie_id+`>`+movielist[i].movie_name+`</option>`
        }
        document.getElementById("selectMID").innerHTML= output;
    })
.catch(error => console.log(error));



function fetchaddshowing(evt) {
    evt.preventDefault();
    addMid = document.getElementById("selectMID");
    addAmount = document.getElementById("Amount");
    addSfrom = document.getElementById("Sfrom");
    addSto = document.getElementById("Sto");
    var fd = new FormData();
    fd.append('Mid', addMid.value);
    fd.append('Amount', addAmount.value); 
    fd.append('Sfrom', addSfrom.value);
    fd.append('Sto', addSto.value); 
    fetch('http://localhost:80/2021/PROJ2/api/api.php?action=addshowing', 
    {
        method: 'POST',
        body: fd,
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status == 400) {
            console.log('addshowing failed');
            return;
        }
        if(headers.status == 201) {
            console.log('newshowing updated');
            return;
        }
    })
    .catch(error => console.log(error));
}


function fetchlogout(evt) {
    fetch('http://localhost:80/2021/PROJ2/api/api.php?action=logout', 
    {
        method: 'GET',
        credentials: 'include'
    })
    .then(function(headers) {
        if(headers.status != 200) {
            console.log('logout failed Server-Side, but make client login again');
        }
        localStorage.removeItem('csrf');
        localStorage.removeItem('role');
        localStorage.removeItem('username');
        localStorage.removeItem('email');
        localStorage.removeItem('name');
        localStorage.removeItem('LoginID');
    })
    .catch(error => console.log(error));
}