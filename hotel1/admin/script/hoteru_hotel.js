        
        function get_hotels() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hotels.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                document.getElementById('hotels-data').innerHTML = this.responseText;
            }
            xhr.send('get_hotels');
        }
        function toggle_status(hotel,val) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hotels.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if(this.responseText==1){
                    alert('success','Status toggled');
                    get_users();
                }else{
                    alert('error',this.responseText);
                }
            }
            xhr.send('toggle_status='+hotel+'&value='+val);
        }
        function warn_user(hotel) {
            if(confirm("Are you sure, you want to sent warning to this Hotel account?")){
                let data = new FormData();
                data.append('hotel',hotel);
                data.append('warn_user','');
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/hotels.php", true);
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
        function remove_user(hotel) {
            if(confirm("Are you sure, you want to delete this user account?")){
                let data = new FormData();
                data.append('hotel',hotel);
                data.append('remove_user','');
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/hotels.php", true);
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
        function search_hotel(adminname){
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hotels.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                document.getElementById('hotels-data').innerHTML = this.responseText;
            }
            xhr.send('search_hotel&name='+adminname);
        }
        window.onload = function() {
            get_hotels();
        }