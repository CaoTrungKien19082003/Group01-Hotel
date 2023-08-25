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
    uqr_analytics(1);
}