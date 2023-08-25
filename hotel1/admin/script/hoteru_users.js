        
        function get_users() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/users.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                document.getElementById('users-data').innerHTML = this.responseText;
            }
            xhr.send('get_users');
        }
        function toggle_status(id,val) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/users.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if(this.responseText==1){
                    alert('success','Status toggled');
                    get_users();
                }else{
                    alert('error','Server Down!');
                }
            }
            xhr.send('toggle_status='+id+'&value='+val);
        }
        function warn_user(id) {
            if(confirm("Are you sure, you want to sent warning to this user account?")){
                let data = new FormData();
                data.append('id',id);
                data.append('warn_user','');
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/users.php", true);
                xhr.onload = function() {
                    if (this.responseText == 1) {
                        alert('success', 'User warned!');
                        get_users();
                    } 
                    else {
                        alert('error', 'User warned failed!');
                    }
                }
                xhr.send(data);
            }
        }
        function remove_user(id) {
            if(confirm("Are you sure, you want to delete this user account?")){
                let data = new FormData();
                data.append('id',id);
                data.append('remove_user','');
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/users.php", true);
                xhr.onload = function() {
                    if (this.responseText == 1) {
                        alert('success', 'User removed!');
                        get_users();
                    } 
                    else {
                        alert('error', 'User removed failed!');
                    }
                }
                xhr.send(data);
            }
        }
        function search_user(username){
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/users.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                document.getElementById('users-data').innerHTML = this.responseText;
            }
            xhr.send('search_user&name='+username);
        }
        window.onload = function() {
            get_users();
        }