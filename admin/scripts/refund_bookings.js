function get_bookings(search=''){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/refund_bookings.php", true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload=function(){
        document.getElementById('booking_data').innerHTML=this.responseText;
    }

    xhr.send('get_bookings&search='+search);
}

function refund_bookings(id){
    if (confirm("Are you sure you want to refund?")){
        let data=new FormData();
        data.append('booking_id', id);
        data.append('refund_bookings', '');

        let xhr=new XMLHttpRequest();
        xhr.open("POST", "ajax/refund_bookings.php", true);

        xhr.onload=function(){
            if (this.responseText==1){
                
                alert('success', 'Booking is refunded!');
                get_bookings();
            }    
            else{
                alert('error', 'Server is currently down!');
            }
        } 

        xhr.send(data);
    }
}

window.onload=function(){
    get_bookings();
}