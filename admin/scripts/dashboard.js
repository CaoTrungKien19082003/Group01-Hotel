function booking_analytics(period){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/dashboard.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload=function(){
        let data=JSON.parse(this.responseText);
        document.getElementById('total').textContent=data.total;
        document.getElementById('total_amt').textContent='$'+data.total_amt;

        document.getElementById('active').textContent=data.active;
        document.getElementById('active_amt').textContent='$'+data.active_amt;

        document.getElementById('cancelled').textContent=data.cancelled;
        document.getElementById('cancelled_amt').textContent='$'+data.cancelled_amt;
    }

    xhr.send('booking_analytics&period='+period);
}

function uqr_analytics(period){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/dashboard.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload=function(){
        let data=JSON.parse(this.responseText);
        document.getElementById('new_reg').textContent=data.new_reg;
        document.getElementById('user_queries').textContent=data.total_queries;
        document.getElementById('user_reviews').textContent=data.total_reviews;
    }
    xhr.send('uqr_analytics&period='+period);
}

window.onload=function(){
    booking_analytics(1);
    uqr_analytics(1);
}