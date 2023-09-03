function get_bookings(search=''){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload=function(){
        document.getElementById('booking_data').innerHTML=this.responseText;
    }

    xhr.send('get_bookings&search='+search);
}

let assign_room_form=document.getElementById('assign_room_form');



window.onload=function(){
    get_bookings();
}