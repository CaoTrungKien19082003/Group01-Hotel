function booking_analytics(period){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/hoteru_dashboard_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload=function(){
        let data=JSON.parse(this.responseText);
        document.getElementById('total').textContent=data.total;

        document.getElementById('active').textContent=data.active;

        document.getElementById('cancelled').textContent=data.cancelled;
    }

    xhr.send('booking_analytics&period='+period);
}
function uqr_analytics(period){
    let xhr=new XMLHttpRequest();
    xhr.open("POST", "ajax/hoteru_dashboard_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload=function(){
        let data=JSON.parse(this.responseText);
        document.getElementById('user_queries').textContent=data.total_queries;
        document.getElementById('user_reviews').textContent=data.total_reviews;
    }
    xhr.send('uqr_analytics&period='+period);
}
window.onload=function(){
    booking_analytics(1);
    uqr_analytics(1);
}