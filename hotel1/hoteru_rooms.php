<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteru - Rooms</title>
    <?php require('inc1/links.php');?>
</head>

<body>
    <?php 
        require('inc1/header.php');
        $checkin_default="";
        $checkout_default="";
        $locat_default="";
        $adult_default=1;
        $children_default=0;
        if(isset($_GET['chk_availability'])){
            $frm_data = filteration($_GET);
            $checkin_default = $frm_data['checkin'];
            $checkout_default = $frm_data['checkout'];
            $locat_default=$frm_data['add'];
            $adult_default=1;
            $children_default=0;
        }
    ?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>
        
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 clo-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">FILTERS</h4>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column mt-2 align-items-stretch" id="filterDropdown">
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px">
                                    <span>CHECK AVAILABILITY</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <label class="form-label">Check-in</label>
                                <input type="Date" class="form-control shadow-none mb-3" value="<?php echo $checkin_default?>" id="checkin" onchange="chk_avail_filter()">
                                <label class="form-label">Check-out</label>
                                <input type="Date" class="form-control shadow-none mb-3" value="<?php echo $checkout_default?>" id="checkout" onchange="chk_avail_filter()">                             
                            </div>
                        </div>
                        <div class="border bg-light p-3 rounded mb-3">
                            <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px">
                                <span>Max price</span>
                                <button id="max_btn" onclick="max_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                            </h5>
                            <input type="number" min="0" class="form-control shadow-none mb-2" id="max" oninput="max_filter()">
                        </div>
                        <div class="border bg-light p-3 rounded mb-3">
                            <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px">
                                <span>City Or Province</span>
                                <button id="locat_btn" onclick="locat_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                            </h5>
                            <textarea id="add" class="form-control shadow-none" rows="1" oninput="locat_filter()"><?php echo $locat_default?></textarea>
                        </div>
                        <div class="border bg-light p-3 rounded mb-3">
                            <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px">
                                <span>Guest</span>
                                <button id="guest_btn" onclick="guest_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                            </h5>
                            <div class="d-flex">
                                <div claas="me-3">
                                    <label class="form-label">Adults</label>
                                    <input type="number" min="1" class="form-control shadow-none mb-2" value="<?php echo $adult_default?>" id="adult" oninput="guest_filter()">
                                </div>
                                <div>
                                    <label class="form-label">Children</label>
                                    <input type="number" min="0" class="form-control shadow-none mb-2" id="children" value="<?php echo $children_default?>" oninput="guest_filter()">
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9 col-md-12 px-4" id="room-data">
                
            </div>
        </div>
    </div>
    <script>
        let room_data = document.getElementById('room-data');
        let checkin = document.getElementById('checkin');
        let checkout = document.getElementById('checkout');
        let chk_avail_btn = document.getElementById('chk_avail_btn');
        let max_price = document.getElementById('max');
        let max_btn = document.getElementById('max_btn');
        let add = document.getElementById('add');
        let locat_btn = document.getElementById('locat_btn')
        let adult = document.getElementById('adult');
        let children = document.getElementById('children');
        let guest_btn = document.getElementById('guest_btn');
        function fetch_room(){
            let chk_avail = JSON.stringify({
                checkin: checkin.value,
                checkout: checkout.value
            });
            let max = JSON.stringify({
                max_p: max_price.value
            });
            let location = JSON.stringify({
                locat: add.value
            });
            let guest = JSON.stringify({
                adult: adult.value,
                children: children.value
            });
            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/rooms.php?fetch_room&chk_avail="+chk_avail+"&max_p="+max+"&locat="+location+"&guest="+guest,true);
            xhr.onprogress =function(){
                room_data.innerHTML = `<div class="spinner-border text-dark mb-3 d-block mx-auto" id="loader" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>`;
            }
            xhr.onload = function(){
                room_data.innerHTML = this.responseText;

            }
            xhr.send();
        }
        function chk_avail_filter(){
            if(checkin.value != '' && checkout.value != ''){
                fetch_room();
                chk_avail_btn.classList.remove('d-none')
            }
        }
        function chk_avail_clear(){
            checkin.value = '';
            checkout.value = '';
            chk_avail_btn.classList.add('d-none')
            fetch_room();  
        }
        function max_filter(){
            if(max.value>0){
                fetch_room();
                max_btn.classList.remove('d-none')
            }
        }
        function max_clear(){
            max.value = '';
            max_btn.classList.add('d-none')
            fetch_room();  
        }
        function locat_filter(){
            if(add.value!=''){
                fetch_room();
                locat_btn.classList.remove('d-none')
            }
        }
        function locat_clear(){
            add.value = '';
            locat_btn.classList.add('d-none')
            fetch_room();  
        }
        function guest_filter(){
            if(adult.value>0 || children.value>=0){
                fetch_room();
                guest_btn.classList.remove('d-none')
            }
        }
        function guest_clear(){
            adult.value = '';
            children.value = '';
            guest_btn.classList.add('d-none')
            fetch_room();  
        }
        fetch_room();
    </script>
    <?php require('inc1/footer.php');?>

</body>

</html>